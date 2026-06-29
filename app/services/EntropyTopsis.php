<?php
class EntropyTopsis
{
    private float $userLat;
    private float $userLng;
    private array $hotels = [];
    private array $matrix = [];
    private int $n; // alternatives
    private int $m; // criteria

    // criterion: [index, name, type, weight_override(0=auto)]
    private array $criteria = [
        ['price_start', 'Harga',       'cost',    0],
        ['rating_avg',  'Rating',      'benefit', 0],
        ['facilities_count', 'Fasilitas', 'benefit', 0],
        ['distance', 'Jarak',       'cost',    0],
        ['occupancy','Tingkat Keramaian', 'cost', 0],
    ];

    public function __construct(float $userLat, float $userLng)
    {
        $this->userLat = $userLat;
        $this->userLng = $userLng;
    }

    public function calculate(): array
    {
        $this->loadHotels();
        if (empty($this->hotels)) return [];
        $this->buildMatrix();
        $normalized = $this->normalize();
        $weights = $this->entropyWeights($normalized);
        $weighted = $this->weightedMatrix($normalized, $weights);
        [$idealPos, $idealNeg] = $this->idealSolutions($weighted);
        $distances = $this->distance($weighted, $idealPos, $idealNeg);
        $results = $this->rank($distances, $weights);

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
        $this->hotels = Database::fetchAll("
            SELECT h.*,
                   (SELECT COUNT(*) FROM hotel_facilities WHERE hotel_id = h.id) as facilities_count
            FROM hotels h
            WHERE h.status = 'verified'
        ");

        foreach ($this->hotels as $hotel) {
            $roomData = Database::fetch(
                "SELECT COALESCE(SUM(total_room), 0) as total, COALESCE(SUM(occupied_room), 0) as occupied
                 FROM rooms WHERE hotel_id = ?",
                [$hotel->id]
            );
            $occ = ($roomData && $roomData->total > 0) ? round(($roomData->occupied / $roomData->total) * 100) : 0;
            $hotel->occupancy = (int)$occ;
            $hotel->distance = $this->haversine(
                $this->userLat, $this->userLng,
                $hotel->latitude, $hotel->longitude
            );
        }
    }

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function buildMatrix(): void
    {
        $this->n = count($this->hotels);
        $this->m = count($this->criteria);

        foreach ($this->hotels as $i => $hotel) {
            foreach ($this->criteria as $j => $c) {
                $this->matrix[$i][$j] = (float) $hotel->{$c[0]};
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
        // normalisasi untuk entropy (sum = 1 per kolom)
        $p = [];
        for ($j = 0; $j < $this->m; $j++) {
            $colSum = array_sum(array_column($normalized, $j));
            for ($i = 0; $i < $this->n; $i++) {
                $p[$i][$j] = $colSum > 0 ? $normalized[$i][$j] / $colSum : 0;
            }
        }

        $k = 1 / log($this->n);
        $e = [];
        for ($j = 0; $j < $this->m; $j++) {
            $e[$j] = 0;
            for ($i = 0; $i < $this->n; $i++) {
                if ($p[$i][$j] > 0) {
                    $e[$j] -= $k * $p[$i][$j] * log($p[$i][$j]);
                }
            }
        }

        $d = array_map(fn($v) => 1 - $v, $e);
        $dSum = array_sum($d);
        $w = array_map(fn($v) => $dSum > 0 ? $v / $dSum : 0, $d);

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
            $col = array_column($weighted, $j);
            $isCost = $this->criteria[$j][2] === 'cost';
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
            $sum = $distances[$i]['pos'] + $distances[$i]['neg'];
            $score = $sum > 0 ? $distances[$i]['neg'] / $sum : 0;
            $results[] = [
                'hotel' => $this->hotels[$i],
                'score' => round($score, 5),
                'd_pos' => round($distances[$i]['pos'], 5),
                'd_neg' => round($distances[$i]['neg'], 5),
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
