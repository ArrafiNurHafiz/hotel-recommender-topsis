<div class="container py-4">
    <h2 class="fw-bold mb-4">Daftar Hotel</h2>

    <form class="row g-2 mb-4 card p-4" style="background:var(--surface)">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari hotel..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="location" class="form-control" placeholder="Lokasi..." value="<?= htmlspecialchars($filters['location'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <input type="number" name="price_min" class="form-control" placeholder="Harga min" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <input type="number" name="price_max" class="form-control" placeholder="Harga max" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>">
        </div>
        <div class="col-md-1">
            <button class="btn btn-gold w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <?php if (empty($hotels)): ?>
        <div class="text-center py-5">
            <div class="empty-state"><i class="bi bi-building-slash"></i></div>
            <p class="mt-3 text-muted">Tidak ada hotel yang ditemukan</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($hotels as $h): ?>
                <div class="col-md-4">
                    <a href="/hotels/<?= $h->id ?>" class="text-decoration-none">
                        <div class="card hotel-card">
                            <div class="overflow-hidden"><img src="<?= !empty($h->image) ? htmlspecialchars($h->image) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80' ?>" class="card-img-top" alt="<?= htmlspecialchars($h->name) ?>"></div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="card-title text-dark mb-0"><?= htmlspecialchars($h->name) ?></h5>
                                    <?php $occ = Hotel::getOccupancy($h->id); ?>
                                    <?php if ($occ <= 40): ?>
                                        <span class="occupancy-badge occ-sepi">Sepi</span>
                                    <?php elseif ($occ <= 70): ?>
                                        <span class="occupancy-badge occ-sedang">Sedang</span>
                                    <?php else: ?>
                                        <span class="occupancy-badge occ-ramai">Ramai</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text text-muted small">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars(substr($h->address, 0, 60)) ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-gold">Rp <?= number_format($h->price_start) ?>+</span>
                                    <span class="rating-badge"><i class="bi bi-star-fill"></i> <?= $h->rating_avg ?: '0.0' ?></span>
                                </div>
                                <?php if (!empty($h->facilities)): ?>
                                    <div class="mt-2">
                                        <?php foreach (array_slice($h->facilities, 0, 3) as $f): ?>
                                            <span class="badge badge-soft"><?= htmlspecialchars($f->name) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($h->facilities) > 3): ?>
                                            <span class="badge badge-soft">+<?= count($h->facilities) - 3 ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination->lastPage > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination->lastPage; $i++): ?>
                        <li class="page-item <?= $i === $pagination->page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&location=<?= urlencode($filters['location']) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
