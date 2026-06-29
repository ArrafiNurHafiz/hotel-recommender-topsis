<?php
class EntropyTopsis
{
    private array $hotels = [];
    private array $matrix = [];
    private int $n; // alternatives
    private int $m; // criteria

    // criterion: [column, name, type]
    // type 'cost' = lower is better, 'benefit' = higher is better
    private array $criteria = [
        ['price_start',      'Harga',          'cost'],
        ['rating_avg',       'Rating',         'benefit'],
        ['facilities_count', 'Fasilitas',      'benefit'],
        ['occupancy',        'Tingkat Keramaian', 'cost'],
    ];

    private ?string $city;

    public function __construct(?string $city = null)
    {
        $this->city = $city;
    }

    public function calculate(): array
    {
        $this->loadHotels();
        if (empty($this->hotels)) return [];
        if (count($this->hotels) < 2) {
            // TOPSIS needs at least 2 alternatives; return single result with score=1
            return [[
                'hotel'   => $this->hotels[0],
                'score'   => 1.0,
                'd_pos'   => 0.0,
                'd_neg'   => 0.0,
                'weights' => [0.25, 0.25, 0.25, 0.25],
                'rank'    => 1,
            ]];
        }

        $this->buildMatrix();
        $normalized = $this->normalize();
        $weights    = $this->entropyWeights($normalized);
        $weighted   = $this->weightedMatrix($normalized, $weights);
        [$idealPos, $idealNeg] = $this->idealSolutions($weighted);
        $distances  = $this->distance($weighted, $idealPos, $idealNeg);
        $results    = $this->rank($distances, $weights);

        // Persist to DB (only when user is logged in)
        $userId = Auth::id();
        if ($userId) {
            Database::delete('recommendations', 'user_id = ?', [$userId]);
            foreach ($results as $i => $r) {
                Database::insert('recommendations', [
                    'user_id'       => $userId,
                    'hotel_id'      => $r['hotel']->id,
                    'entropy_score' => $weights[$i] ?? 0,
                    'topsis_score'  => $r['score'],
                    'rank'          => $i + 1,
                ]);
            }
        }

        return $results;
    }

    private function loadHotels(): void
    {
        $whereExtra = '';
        $params     = [];
        if ($this->city) {
            $whereExtra = " AND h.address LIKE ?";
            $params[]   = '%' . $this->city . '%';
        }

        $this->hotels = Database::fetchAll("
            SELECT h.*,
                   (SELECT COUNT(*) FROM hotel_facilities WHERE hotel_id = h.id) AS facilities_count
            FROM hotels h
            WHERE h.status = 'verified'$whereExtra
            ORDER BY h.id
        ", $params);

        foreach ($this->hotels as $hotel) {
            $roomData = Database::fetch(
                "SELECT COALESCE(SUM(total_room),0) AS total, COALESCE(SUM(occupied_room),0) AS occupied
                 FROM rooms WHERE hotel_id = ?",
                [$hotel->id]
            );
            $occ = ($roomData && $roomData->total > 0)
                ? round(($roomData->occupied / $roomData->total) * 100)
                : 0;
            $hotel->occupancy = (int)$occ;
        }
    }

    private function buildMatrix(): void
    {
        $this->n = count($this->hotels);
        $this->m = count($this->criteria);

        foreach ($this->hotels as $i => $hotel) {
            foreach ($this->criteria as $j => $c) {
                $this->matrix[$i][$j] = (float)$hotel->{$c[0]};
            }
        }
    }

    private function normalize(): array
    {
        $normalized = [];
        for ($j = 0; $j < $this->m; $j++) {
            $sumSq = 0;
            for ($i = 0; $i < $this->n; $i++) {
                $sumSq += $this->matrix[$i][$j] ** 2;
            }
            $norm = sqrt($sumSq);
            for ($i = 0; $i < $this->n; $i++) {
                $normalized[$i][$j] = $norm > 0 ? $this->matrix[$i][$j] / $norm : 0;
            }
        }
        return $normalized;
    }

    private function entropyWeights(array $normalized): array
    {
        $p = [];
        for ($j = 0; $j < $this->m; $j++) {
            $colSum = array_sum(array_column($normalized, $j));
            for ($i = 0; $i < $this->n; $i++) {
                $p[$i][$j] = $colSum > 0 ? $normalized[$i][$j] / $colSum : 0;
            }
        }

        $k = $this->n > 1 ? 1 / log($this->n) : 0;
        $e = [];
        for ($j = 0; $j < $this->m; $j++) {
            $e[$j] = 0;
            for ($i = 0; $i < $this->n; $i++) {
                if ($p[$i][$j] > 0) {
                    $e[$j] -= $k * $p[$i][$j] * log($p[$i][$j]);
                }
            }
        }

        $d    = array_map(fn($v) => 1 - $v, $e);
        $dSum = array_sum($d);
        $w    = array_map(fn($v) => $dSum > 0 ? $v / $dSum : 0, $d);

        return $w;
    }

    private function weightedMatrix(array $normalized, array $weights): array
    {
        $weighted = [];
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
            }
        }
        return $weighted;
    }

    private function idealSolutions(array $weighted): array
    {
        $idealPos = [];
        $idealNeg = [];
        for ($j = 0; $j < $this->m; $j++) {
            $col     = array_column($weighted, $j);
            $isCost  = $this->criteria[$j][2] === 'cost';
            $idealPos[$j] = $isCost ? min($col) : max($col);
            $idealNeg[$j] = $isCost ? max($col) : min($col);
        }
        return [$idealPos, $idealNeg];
    }

    private function distance(array $weighted, array $idealPos, array $idealNeg): array
    {
        $distances = [];
        for ($i = 0; $i < $this->n; $i++) {
            $dPos = 0;
            $dNeg = 0;
            for ($j = 0; $j < $this->m; $j++) {
                $dPos += ($weighted[$i][$j] - $idealPos[$j]) ** 2;
                $dNeg += ($weighted[$i][$j] - $idealNeg[$j]) ** 2;
            }
            $distances[$i] = [
                'pos' => sqrt($dPos),
                'neg' => sqrt($dNeg),
            ];
        }
        return $distances;
    }

    private function rank(array $distances, array $weights): array
    {
        $results = [];
        for ($i = 0; $i < $this->n; $i++) {
            $sum   = $distances[$i]['pos'] + $distances[$i]['neg'];
            $score = $sum > 0 ? $distances[$i]['neg'] / $sum : 0;
            $results[] = [
                'hotel'   => $this->hotels[$i],
                'score'   => round($score, 5),
                'd_pos'   => round($distances[$i]['pos'], 5),
                'd_neg'   => round($distances[$i]['neg'], 5),
                'weights' => $weights,
            ];
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        foreach ($results as $i => &$r) {
            $r['rank'] = $i + 1;
        }

        return $results;
    }
}
