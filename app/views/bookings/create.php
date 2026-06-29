<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Booking Kamar</h4>
                    <h6 class="text-muted"><?= htmlspecialchars($hotel->name) ?> — <?= htmlspecialchars($room->room_type) ?></h6>
                    <p class="text-gold fw-bold">Rp <?= number_format($room->price) ?> / malam</p>

                    <?php if (isset($errors)): ?>
                        <?php foreach ($errors as $e): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <form method="POST" action="/booking">
                        <input type="hidden" name="room_id" value="<?= $room->id ?>">
                        <div class="mb-3">
                            <label class="form-label">Check-in</label>
                            <input type="date" name="check_in" class="form-control" value="<?= htmlspecialchars($old['check_in'] ?? date('Y-m-d')) ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check-out</label>
                            <input type="date" name="check_out" class="form-control" value="<?= htmlspecialchars($old['check_out'] ?? date('Y-m-d', strtotime('+1 day'))) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Konfirmasi Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
