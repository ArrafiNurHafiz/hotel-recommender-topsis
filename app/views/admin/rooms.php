<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-building"></i> Admin Panel</h5>
            <a href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/hotels/edit"><i class="bi bi-building"></i> Profil Hotel</a>
            <a href="/admin/rooms" class="active"><i class="bi bi-door-open"></i> Kelola Kamar</a>
            <a href="/admin/bookings"><i class="bi bi-calendar-check"></i> Booking</a>
            <a href="/admin/reviews"><i class="bi bi-chat-square-text"></i> Review</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Kelola Kamar</h4>
                <button class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#roomModal">+ Tambah Kamar</button>
            </div>

            <?php if (empty($rooms)): ?>
                <p class="text-muted">Belum ada kamar. Tambahkan kamar baru.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Tipe Kamar</th><th>Harga</th><th>Total</th><th>Terisi</th><th>Tersedia</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r->room_type) ?></td>
                                    <td class="fw-bold">Rp <?= number_format($r->price) ?></td>
                                    <td><?= $r->total_room ?></td>
                                    <td><?= $r->occupied_room ?></td>
                                    <td><?= $r->total_room - $r->occupied_room ?></td>
                                    <td><span class="badge <?= $r->is_active ? 'bg-green' : 'bg-gray' ?>"><?= $r->is_active ? 'Aktif' : 'Nonaktif' ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-gold" data-bs-toggle="modal" data-bs-target="#roomModal<?= $r->id ?>">Edit</button>
                                        <form method="POST" action="/admin/rooms/delete" class="d-inline" onsubmit="return confirm('Hapus kamar ini?')">
                                            <input type="hidden" name="id" value="<?= $r->id ?>">
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php foreach ($rooms as $r): ?>
                    <div class="modal fade" id="roomModal<?= $r->id ?>">
                        <div class="modal-dialog"><form class="modal-content" method="POST" action="/admin/rooms/update">
                            <input type="hidden" name="id" value="<?= $r->id ?>">
                            <div class="modal-header"><h5>Edit Kamar</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <div class="mb-3"><label class="form-label">Tipe Kamar</label><input name="room_type" class="form-control" value="<?= htmlspecialchars($r->room_type) ?>" required></div>
                                <div class="mb-3"><label class="form-label">Harga</label><input type="number" name="price" class="form-control" value="<?= $r->price ?>" required></div>
                                <div class="mb-3"><label class="form-label">Jumlah Kamar</label><input type="number" name="total_room" class="form-control" value="<?= $r->total_room ?>" required></div>
                                <div class="mb-3"><label class="form-label">Terisi</label><input type="number" name="occupied_room" class="form-control" value="<?= $r->occupied_room ?>" required></div>
                                <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active<?= $r->id ?>" <?= $r->is_active ? 'checked' : '' ?>><label class="form-check-label" for="active<?= $r->id ?>">Aktif</label></div>
                            </div>
                            <div class="modal-footer"><button class="btn btn-gold">Simpan</button></div>
                        </form></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="modal fade" id="roomModal">
                <div class="modal-dialog"><form class="modal-content" method="POST" action="/admin/rooms/store">
                    <div class="modal-header"><h5>Tambah Kamar</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Tipe Kamar</label><input name="room_type" class="form-control" placeholder="Standard Room" required></div>
                        <div class="mb-3"><label class="form-label">Harga per Malam</label><input type="number" name="price" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Jumlah Kamar</label><input type="number" name="total_room" class="form-control" value="5" required></div>
                        <div class="mb-3"><label class="form-label">Terisi</label><input type="number" name="occupied_room" class="form-control" value="0"></div>
                        <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="activeNew" checked><label class="form-check-label" for="activeNew">Aktif</label></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-gold">Simpan</button></div>
                </form></div>
            </div>
        </div>
    </div>
</div>
