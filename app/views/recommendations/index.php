<div class="container py-4">
    <h2 class="fw-bold mb-1">Rekomendasi Hotel</h2>
    <p class="text-muted mb-4">Perangkingan menggunakan metode <strong>Hybrid Entropy-TOPSIS</strong> berdasarkan harga, rating, fasilitas, dan tingkat keramaian.</p>

    <!-- Filter Kota -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="/recommendations">
                <div class="col-md-5">
                    <label class="form-label fw-semibold" for="city-select">
                        <i class="bi bi-geo-alt-fill text-warning"></i> Filter Berdasarkan Kota
                    </label>
                    <select id="city-select" name="city" class="form-select">
                        <option value="">— Semua Kota —</option>
                        <?php foreach ($validCities as $c): ?>
                            <option value="<?= $c ?>" <?= $city === $c ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-gold w-100" type="submit">
                        <i class="bi bi-search"></i> Tampilkan Rekomendasi
                    </button>
                </div>
                <?php if ($city): ?>
                <div class="col-md-2">
                    <a href="/recommendations" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (empty($results)): ?>
        <div class="text-center py-5">
            <div class="empty-state-illustration"><i class="bi bi-building"></i></div>
            <h5 class="text-muted mt-3">Tidak ada hotel ditemukan</h5>
            <p class="text-muted">Coba pilih kota lain atau tampilkan semua kota.</p>
        </div>
    <?php else: ?>

        <?php if ($city): ?>
        <p class="text-muted small mb-3">
            Menampilkan <strong><?= count($results) ?> hotel</strong> di kota <strong><?= htmlspecialchars($city) ?></strong>, diurutkan berdasarkan skor TOPSIS.
        </p>
        <?php else: ?>
        <p class="text-muted small mb-3">
            Menampilkan <strong><?= count($results) ?> hotel</strong> dari semua kota, diurutkan berdasarkan skor TOPSIS.
        </p>
        <?php endif; ?>

        <div class="row g-4">
            <?php foreach ($results as $r): ?>
                <div class="col-md-6">
                    <a href="/hotels/<?= $r['hotel']->id ?>" class="text-decoration-none">
                        <div class="card hotel-card h-100">
                            <?php if ($r['hotel']->image): ?>
                            <img src="<?= htmlspecialchars($r['hotel']->image) ?>"
                                 class="card-img-top"
                                 alt="<?= htmlspecialchars($r['hotel']->name) ?>"
                                 style="height:180px;object-fit:cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rank-badge <?= $r['rank'] <= 3 ? 'rank-' . $r['rank'] : 'rank-other' ?>">
                                        <?= $r['rank'] ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($r['hotel']->name) ?></h5>
                                            <span class="rating-badge ms-2 flex-shrink-0">
                                                <i class="bi bi-star-fill"></i> <?= $r['hotel']->rating_avg ?: '0.0' ?>
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($r['hotel']->address) ?>
                                        </p>
                                        <div class="d-flex gap-3 text-muted small mb-3">
                                            <span><i class="bi bi-cash"></i> Rp <?= number_format($r['hotel']->price_start) ?></span>
                                            <span><i class="bi bi-people"></i> <?= $r['hotel']->occupancy ?>% Terisi</span>
                                        </div>
                                        <div class="progress progress-sm mb-1">
                                            <div class="progress-bar progress-bar-gold"
                                                 style="width:<?= $r['score'] * 100 ?>%;">
                                            </div>
                                        </div>
                                        <small class="text-muted">Skor TOPSIS: <strong><?= $r['score'] ?></strong></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bobot Kriteria -->
        <div class="mt-4 p-3 rounded bg-soft">
            <h6 class="fw-semibold mb-3">Bobot Kriteria (Entropy)</h6>
            <div class="weights-bar row text-center g-2">
                <div class="col weight-item">
                    <small>Harga</small><br>
                    <strong><?= isset($results[0]['weights'][0]) ? round($results[0]['weights'][0] * 100) : 0 ?>%</strong>
                </div>
                <div class="col weight-item">
                    <small>Rating</small><br>
                    <strong><?= isset($results[0]['weights'][1]) ? round($results[0]['weights'][1] * 100) : 0 ?>%</strong>
                </div>
                <div class="col weight-item">
                    <small>Fasilitas</small><br>
                    <strong><?= isset($results[0]['weights'][2]) ? round($results[0]['weights'][2] * 100) : 0 ?>%</strong>
                </div>
                <div class="col weight-item">
                    <small>Keramaian</small><br>
                    <strong><?= isset($results[0]['weights'][3]) ? round($results[0]['weights'][3] * 100) : 0 ?>%</strong>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
