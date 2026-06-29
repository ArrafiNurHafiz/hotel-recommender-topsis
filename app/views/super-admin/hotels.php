<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-shield-lock"></i> Super Admin</h5>
            <a href="/super-admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/super-admin/hotels" class="active"><i class="bi bi-building-check"></i> Verifikasi Hotel</a>
            <a href="/super-admin/users"><i class="bi bi-people"></i> Users</a>
            <a href="/super-admin/monitoring"><i class="bi bi-bar-chart"></i> Monitoring</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Manajemen Hotel</h4>
                <div>
                    <a href="?status=pending" class="btn btn-sm btn-outline-warning">Pending</a>
                    <a href="?status=verified" class="btn btn-sm btn-outline-success">Terverifikasi</a>
                    <a href="?status=all" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>
            </div>

            <?php if (empty($hotels)): ?>
                <p class="text-muted">Tidak ada hotel</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Nama</th><th>Admin</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hotels as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h->name) ?></td>
                                    <td><small><?= htmlspecialchars($h->admin_name ?? '-') ?></small></td>
                                    <td><small class="text-muted"><?= htmlspecialchars(substr($h->address, 0, 40)) ?>...</small></td>
                                    <td>
                                        <?php if ($h->status === 'verified'): ?>
                                            <span class="badge bg-green">Terverifikasi</span>
                                        <?php elseif ($h->status === 'pending'): ?>
                                            <span class="badge bg-yellow">Pending</span>
                                        <?php elseif ($h->status === 'rejected'): ?>
                                            <span class="badge bg-red">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-gray"><?= $h->status ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($h->status === 'pending'): ?>
                                            <form method="POST" action="/super-admin/hotels/verify" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $h->id ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button class="btn btn-sm btn-success" onclick="return confirm('Setujui hotel ini?')">Setujui</button>
                                            </form>
                                            <form method="POST" action="/super-admin/hotels/verify" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $h->id ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak hotel ini?')">Tolak</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
