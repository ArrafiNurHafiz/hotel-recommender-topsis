<div class="container py-4">
    <h2 class="fw-bold mb-2">Rekomendasi Hotel</h2>
    <p class="text-muted mb-4">Hasil perangkingan menggunakan metode Hybrid Entropy-TOPSIS berdasarkan harga, rating, fasilitas, jarak, dan tingkat keramaian.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="/recommendations">
                <div class="col-md-4">
                    <label class="form-label small">Lokasi Saya (Latitude)</label>
                    <input type="number" step="any" name="lat" class="form-control" value="<?= htmlspecialchars($userLat) ?>" placeholder="-6.2088">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Lokasi Saya (Longitude)</label>
                    <input type="number" step="any" name="lng" class="form-control" value="<?= htmlspecialchars($userLng) ?>" placeholder="106.8456">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-gold w-100">Hitung Rekomendasi</button>
                </div>
                <div class="col-12">
                    <small class="text-muted">Contoh koordinat: Batam (-6.2088, 106.8456), Jakarta (-6.2088, 106.8456), Bandung (-6.9175, 107.6191)</small>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($results)): ?>
        <div class="text-center py-5">
            <div class="empty-state-illustration"><i class="bi bi-search"></i></div>
            <p class="text-muted">Masukkan lokasi Anda untuk mendapatkan rekomendasi</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($results as $r): ?>
                <div class="col-md-6">
                    <a href="/hotels/<?= $r['hotel']->id ?>" class="text-decoration-none">
                        <div class="card hotel-card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rank-badge <?= $r['rank'] <= 3 ? 'rank-' . $r['rank'] : 'rank-other' ?>">
                                        <?= $r['rank'] ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($r['hotel']->name) ?></h5>
                                            <span class="rating-badge"><i class="bi bi-star-fill"></i> <?= $r['hotel']->rating_avg ?: '0.0' ?></span>
                                        </div>
                                        <div class="d-flex gap-3 text-muted small mb-2">
                                            <span><i class="bi bi-cash"></i> Rp <?= number_format($r['hotel']->price_start) ?></span>
                                            <span><i class="bi bi-geo-alt"></i> <?= round($r['hotel']->distance, 1) ?> km</span>
                                            <span><i class="bi bi-people"></i> <?= $r['hotel']->occupancy ?>%</span>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar progress-bar-gold" style="width:<?= $r['score'] * 100 ?>%;"></div>
                                        </div>
                                        <small class="text-muted">Skor TOPSIS: <?= $r['score'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 p-3 rounded bg-soft">
            <h6 class="fw-semibold">Bobot Kriteria (Entropy)</h6>
            <div class="weights-bar row text-center g-2">
                <div class="col weight-item"><small>Harga</small><br><strong><?= round($results[0]['weights'][0] * 100) ?>%</strong></div>
                <div class="col weight-item"><small>Rating</small><br><strong><?= round($results[0]['weights'][1] * 100) ?>%</strong></div>
                <div class="col weight-item"><small>Fasilitas</small><br><strong><?= round($results[0]['weights'][2] * 100) ?>%</strong></div>
                <div class="col weight-item"><small>Jarak</small><br><strong><?= round($results[0]['weights'][3] * 100) ?>%</strong></div>
                <div class="col weight-item"><small>Keramaian</small><br><strong><?= round($results[0]['weights'][4] * 100) ?>%</strong></div>
            </div>
        </div>
    <?php endif; ?>
</div>
