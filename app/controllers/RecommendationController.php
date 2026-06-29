<?php
require_once __DIR__ . '/../services/EntropyTopsis.php';

class RecommendationController extends Controller
{
    public function index(): void
    {
        $userLat  = (float)($_GET['lat'] ?? -6.2088);
        $userLng  = (float)($_GET['lng'] ?? 106.8456);

        $topsis = new EntropyTopsis($userLat, $userLng);
        $results = $topsis->calculate();
        $userHasPreferences = isset($_GET['lat']);

        $this->view('recommendations/index', [
            'title'    => 'Rekomendasi Hotel',
            'results'  => $results,
            'userPref' => $userHasPreferences,
            'userLat'  => $userLat,
            'userLng'  => $userLng,
        ]);
    }
}
