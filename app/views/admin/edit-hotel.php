<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-building"></i> Admin Panel</h5>
            <a href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/hotels/edit" class="active"><i class="bi bi-building"></i> Profil Hotel</a>
            <a href="/admin/rooms"><i class="bi bi-door-open"></i> Kelola Kamar</a>
            <a href="/admin/bookings"><i class="bi bi-calendar-check"></i> Booking</a>
            <a href="/admin/reviews"><i class="bi bi-chat-square-text"></i> Review</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4"><?= $hotel ? 'Edit' : 'Tambah' ?> Hotel</h4>
            <?php if (isset($errors)): ?>
                <?php foreach ($errors as $e): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="/admin/hotels/update">
                        <div class="mb-3">
                            <label class="form-label">Nama Hotel</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($hotel->name ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($hotel->address ?? '') ?></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="latitude" class="form-control" value="<?= htmlspecialchars($hotel->latitude ?? '-6.2088') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="longitude" class="form-control" value="<?= htmlspecialchars($hotel->longitude ?? '106.8456') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Mulai (Rp)</label>
                                <input type="number" name="price_start" class="form-control" value="<?= htmlspecialchars($hotel->price_start ?? '') ?>">
                            </div>
                        </div>
                        <?php if (isset($facilities)): ?>
                            <div class="mb-3">
                                <label class="form-label">Fasilitas</label>
                                <div class="row g-2">
                                    <?php foreach ($facilities as $f): ?>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="facilities[]" value="<?= $f->id ?>" class="form-check-input" id="fac<?= $f->id ?>"
                                                    <?= in_array($f->id, $selectedFacilities ?? []) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="fac<?= $f->id ?>"><?= htmlspecialchars($f->name) ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <button class="btn btn-gold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
