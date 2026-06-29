<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-shield-lock"></i> Super Admin</h5>
            <a href="/super-admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/super-admin/hotels"><i class="bi bi-building-check"></i> Verifikasi Hotel</a>
            <a href="/super-admin/users"><i class="bi bi-people"></i> Users</a>
            <a href="/super-admin/reports" class="active"><i class="bi bi-bar-chart"></i> Monitoring</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Monitoring & Laporan</h4>

            <div class="card mb-4">
                <div class="card-header fw-bold">Tingkat Okupansi per Hotel</div>
                <div class="card-body">
                    <?php if (empty($occupancyData)): ?>
                        <p class="text-muted mb-0">Belum ada data</p>
                    <?php else: ?>
                        <?php foreach ($occupancyData as $o): ?>
                            <?php $occ = $o->total_rooms > 0 ? ($o->occupied_rooms / $o->total_rooms) * 100 : 0; ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span><?= htmlspecialchars($o->hotel_name) ?></span>
                                    <span class="fw-bold"><?= round($occ) ?>% (<?= $o->occupied_rooms ?>/<?= $o->total_rooms ?>)</span>
                                </div>
                                <div class="progress" style="height:10px;">
                                    <div class="progress-bar <?= $occ > 70 ? 'bg-red' : ($occ > 40 ? 'bg-gold' : 'bg-green') ?>" style="width:<?= $occ ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header fw-bold">Booking per Status</div>
                <div class="card-body">
                    <?php if (!empty($bookingStats)): ?>
                        <div class="row text-center g-3">
                            <div class="col-3"><strong><?= $bookingStats->pending ?? 0 ?></strong><br><small>Pending</small></div>
                            <div class="col-3"><strong><?= $bookingStats->confirmed ?? 0 ?></strong><br><small>Confirmed</small></div>
                            <div class="col-3"><strong><?= $bookingStats->checked_out ?? 0 ?></strong><br><small>Check-out</small></div>
                            <div class="col-3"><strong><?= $bookingStats->cancelled ?? 0 ?></strong><br><small>Cancelled</small></div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Belum ada data booking</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold">Rata-rata Rating Hotel</div>
                <div class="card-body">
                    <?php if (empty($hotelRatings)): ?>
                        <p class="text-muted mb-0">Belum ada review</p>
                    <?php else: ?>
                        <?php foreach ($hotelRatings as $hr): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= htmlspecialchars($hr->hotel_name) ?></span>
                                <span><?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star<?= $i <= round($hr->avg_rating ?? 0) ? '-fill' : '' ?>" style="color:<?= $i <= round($hr->avg_rating ?? 0) ? 'var(--gold)' : 'var(--gray-300)' ?>"></i><?php endfor; ?> <?= number_format($hr->avg_rating ?? 0, 1) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
