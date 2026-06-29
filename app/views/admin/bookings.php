<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-building"></i> Admin Panel</h5>
            <a href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/hotels/edit"><i class="bi bi-building"></i> Profil Hotel</a>
            <a href="/admin/rooms"><i class="bi bi-door-open"></i> Kelola Kamar</a>
            <a href="/admin/bookings" class="active"><i class="bi bi-calendar-check"></i> Booking</a>
            <a href="/admin/reviews"><i class="bi bi-chat-square-text"></i> Review</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Manajemen Booking</h4>

            <?php if (empty($bookings)): ?>
                <p class="text-muted">Belum ada booking</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>#</th><th>Tamu</th><th>Kamar</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $i => $b): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($b->user_name ?? '') ?></td>
                                    <td><?= htmlspecialchars($b->room_type) ?></td>
                                    <td><?= date('d/m/Y', strtotime($b->check_in)) ?></td>
                                    <td><?= date('d/m/Y', strtotime($b->check_out)) ?></td>
                                    <td class="fw-bold">Rp <?= number_format($b->total_price) ?></td>
                                    <td>
                                        <span class="badge <?= match($b->status) { 'pending' => 'bg-yellow', 'confirmed' => 'bg-green', 'checked_out' => 'bg-blue', 'cancelled' => 'bg-red', default => 'bg-gray' } ?>"><?= $b->status ?></span>
                                    </td>
                                    <td>
                                        <?php if ($b->status === 'pending'): ?>
                                            <form method="POST" action="/admin/bookings/confirm" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $b->id ?>">
                                                <button class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi booking ini?')">Konfirmasi</button>
                                            </form>
                                            <form method="POST" action="/admin/bookings/cancel" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $b->id ?>">
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Batalkan booking?')">Tolak</button>
                                            </form>
                                        <?php elseif ($b->status === 'confirmed'): ?>
                                            <form method="POST" action="/admin/bookings/checkout" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $b->id ?>">
                                                <button class="btn btn-sm btn-outline-primary" onclick="return confirm('Tandai check-out?')">Check-out</button>
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
