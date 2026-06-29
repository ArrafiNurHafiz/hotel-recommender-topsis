<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-shield-lock"></i> Super Admin</h5>
            <a href="/super-admin/dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/super-admin/hotels"><i class="bi bi-building-check"></i> Verifikasi Hotel</a>
            <a href="/super-admin/users"><i class="bi bi-people"></i> Users</a>
            <a href="/super-admin/reports"><i class="bi bi-bar-chart"></i> Monitoring</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Dashboard Super Admin</h4>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-building"></i></div>
                        <div class="number"><?= $stats->total_hotels ?></div>
                        <div class="text-muted small">Total Hotel</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-hourglass"></i></div>
                        <div class="number"><?= $stats->pending_hotels ?></div>
                        <div class="text-muted small">Menunggu Verifikasi</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-people"></i></div>
                        <div class="number"><?= $stats->total_users ?></div>
                        <div class="text-muted small">Total User</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-currency-dollar"></i></div>
                        <div class="number">Rp <?= number_format($stats->total_revenue, 0) ?></div>
                        <div class="text-muted small">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <?php if (!empty($recentBookings)): ?>
                <div class="card mt-4">
                    <div class="card-header fw-bold">Booking Terbaru</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead><tr><th>User</th><th>Hotel</th><th>Status</th><th>Total</th></tr></thead>
                            <tbody>
                                <?php foreach ($recentBookings as $b): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($b->user_name ?? '') ?></td>
                                        <td><?= htmlspecialchars($b->hotel_name ?? '') ?></td>
                                        <td><span class="badge bg-<?= $b->status === 'confirmed' ? 'success' : ($b->status === 'pending' ? 'warning text-dark' : 'danger') ?>"><?= $b->status ?></span></td>
                                        <td>Rp <?= number_format($b->total_price) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
