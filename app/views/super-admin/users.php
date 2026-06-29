<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-shield-lock"></i> Super Admin</h5>
            <a href="/super-admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/super-admin/hotels"><i class="bi bi-building-check"></i> Verifikasi Hotel</a>
            <a href="/super-admin/users" class="active"><i class="bi bi-people"></i> Users</a>
            <a href="/super-admin/monitoring"><i class="bi bi-bar-chart"></i> Monitoring</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Manajemen User</h4>
            <?php if (empty($users)): ?>
                <p class="text-muted">Tidak ada user</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Tgl Daftar</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u->name) ?></td>
                                    <td><?= htmlspecialchars($u->email) ?></td>
                                    <td><span class="badge <?= $u->role === 'super_admin' ? 'bg-red' : ($u->role === 'admin_hotel' ? 'bg-blue' : 'bg-gray') ?>"><?= $u->role ?></span></td>
                                    <td><span class="badge <?= ($u->active ?? 1) ? 'bg-green' : 'bg-gray' ?>"><?= ($u->active ?? 1) ? 'Aktif' : 'Nonaktif' ?></span></td>
                                    <td><small><?= date('d M Y', strtotime($u->created_at)) ?></small></td>
                                    <td>
                                        <?php if ($u->role !== 'super_admin'): ?>
                                            <form method="POST" action="/super-admin/users/toggle" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $u->id ?>">
                                                <button class="btn btn-sm btn-outline-warning" onclick="return confirm('Ubah status user ini?')">
                                                    <?= ($u->active ?? 1) ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                </button>
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
