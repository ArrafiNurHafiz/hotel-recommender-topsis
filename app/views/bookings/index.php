<div class="container py-4">
    <h2 class="fw-bold mb-4">Riwayat Booking Saya</h2>

    <?php if (empty($bookings)): ?>
        <div class="text-center py-5 empty-state">
            <i class="bi bi-calendar-x"></i>
            <p class="mt-3 text-muted">Belum ada booking</p>
            <a href="/hotels" class="btn btn-gold">Cari Hotel</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Kamar</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><a href="/hotels/<?= $b->hotel_id ?>"><?= htmlspecialchars($b->hotel_name) ?></a></td>
                            <td><?= htmlspecialchars($b->room_type) ?></td>
                            <td><?= date('d/m/Y', strtotime($b->check_in)) ?></td>
                            <td><?= date('d/m/Y', strtotime($b->check_out)) ?></td>
                            <td class="fw-bold">Rp <?= number_format($b->total_price) ?></td>
                            <td>
                                <?php $badge = match($b->status) {
                                    'pending' => 'bg-yellow',
                                    'confirmed' => 'bg-green',
                                    'checked_out' => 'bg-blue',
                                    'cancelled' => 'bg-red',
                                    default => 'bg-gray'
                                }; ?>
                                <span class="badge <?= $badge ?>"><?= $b->status ?></span>
                            </td>
                            <td>
                                <?php if ($b->status === 'checked_out'): ?>
                                    <button class="btn btn-sm btn-outline-gold" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $b->id ?>">Beri Review</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php foreach ($bookings as $b): ?>
            <?php if ($b->status === 'checked_out'): ?>
                <div class="modal fade" id="reviewModal<?= $b->id ?>">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="/review">
                            <input type="hidden" name="booking_id" value="<?= $b->id ?>">
                            <div class="modal-header">
                                <h5 class="modal-title">Review — <?= htmlspecialchars($b->hotel_name) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select" required>
                                        <option value="">Pilih rating</option>
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <option value="<?= $i ?>"><?= $i ?> <?= str_repeat('⭐', $i) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Komentar</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Bagaimana pengalaman menginap Anda?"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-gold">Kirim Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
