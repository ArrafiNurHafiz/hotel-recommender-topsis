<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/hotels">Hotel</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($hotel->name) ?></li>
        </ol>
    </nav>

    <div class="card mb-4 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80" class="card-img-top" style="height:350px;object-fit:cover;" alt="<?= htmlspecialchars($hotel->name) ?>">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="fw-bold mb-1"><?= htmlspecialchars($hotel->name) ?></h3>
                    <p class="text-muted mb-2"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($hotel->address) ?></p>
                </div>
                <div class="text-end">
                    <div class="rating-badge mb-2"><i class="bi bi-star-fill"></i> <?= $hotel->rating_avg ?: '0.0' ?></div>
                    <div class="fw-bold text-gold">Rp <?= number_format($hotel->price_start) ?>+</div>
                </div>
            </div>

            <?php if ($occupancy <= 40): ?>
                <span class="occupancy-badge occ-sepi"><i class="bi bi-emoji-smile"></i> Sepi</span>
            <?php elseif ($occupancy <= 70): ?>
                <span class="occupancy-badge occ-sedang"><i class="bi bi-emoji-neutral"></i> Sedang</span>
            <?php else: ?>
                <span class="occupancy-badge occ-ramai"><i class="bi bi-emoji-frown"></i> Ramai</span>
            <?php endif; ?>
            <small class="text-muted ms-2"><?= $occupancy ?>% kamar terisi</small>

            <?php if (!empty($hotel->facilities)): ?>
                <div class="mt-3">
                    <h6>Fasilitas</h6>
                    <?php foreach ($hotel->facilities as $f): ?>
                        <span class="badge bg-navy text-gold me-1 p-2"><?= htmlspecialchars($f->name) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Kamar Tersedia</div>
        <div class="card-body">
            <?php if (empty($rooms)): ?>
                <p class="text-muted mb-0">Tidak ada kamar tersedia saat ini</p>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($rooms as $r): ?>
                        <div class="col-md-4">
                            <div class="room-card">
                                <h6 class="fw-bold"><?= htmlspecialchars($r->room_type) ?></h6>
                                <p class="text-gold fw-bold mb-2">Rp <?= number_format($r->price) ?>/malam</p>
                                <small class="text-muted">Sisa <?= $r->total_room - $r->occupied_room ?> kamar</small>
                                <div class="mt-2">
                                    <a href="/booking/<?= $r->id ?>" class="btn btn-gold btn-sm w-100">Booking</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header fw-bold">Review Tamu (<?= count($reviews) ?>)</div>
        <div class="card-body">
            <?php if (empty($reviews)): ?>
                <p class="text-muted mb-0">Belum ada review</p>
            <?php else: ?>
                <?php foreach ($reviews as $rv): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($rv->user_name) ?></strong>
                            <span><?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star<?= $i <= $rv->rating ? '-fill text-warning' : '' ?>"></i><?php endfor; ?></span>
                        </div>
                        <?php if ($rv->comment): ?>
                            <p class="mb-0 mt-1"><?= htmlspecialchars($rv->comment) ?></p>
                        <?php endif; ?>
                        <small class="text-muted"><?= date('d M Y', strtotime($rv->created_at)) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
