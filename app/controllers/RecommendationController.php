<?php
require_once __DIR__ . '/../services/EntropyTopsis.php';

class RecommendationController extends Controller
{
    public function index(): void
    {
        $validCities = [
            'Jakarta', 'Bandung', 'Batam', 'Surabaya', 'Bali',
            'Yogyakarta', 'Medan', 'Makassar', 'Semarang', 'Malang',
            'Lombok', 'Palembang', 'Manado', 'Balikpapan', 'Pontianak',
            'Pekanbaru', 'Solo', 'Labuan Bajo', 'Raja Ampat',
        ];

        $city = $_GET['city'] ?? null;
        if ($city && !in_array($city, $validCities)) {
            $city = null;
        }

        $topsis  = new EntropyTopsis($city);
        $results = $topsis->calculate();

        $this->view('recommendations/index', [
            'title'       => 'Rekomendasi Hotel',
            'results'     => $results,
            'city'        => $city,
            'validCities' => $validCities,
        ]);
    }
}
