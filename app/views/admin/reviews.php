<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-building"></i> Admin Panel</h5>
            <a href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/hotels/edit"><i class="bi bi-building"></i> Profil Hotel</a>
            <a href="/admin/rooms"><i class="bi bi-door-open"></i> Kelola Kamar</a>
            <a href="/admin/bookings"><i class="bi bi-calendar-check"></i> Booking</a>
            <a href="/admin/reviews" class="active"><i class="bi bi-chat-square-text"></i> Review</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Review Tamu</h4>
            <?php if (empty($reviews)): ?>
                <p class="text-muted">Belum ada review</p>
            <?php else: ?>
                <?php foreach ($reviews as $rv): ?>
                    <div class="card mb-2">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($rv->user_name ?? '') ?></strong>
                                <span><?php for ($i = 1; $i <= 5; $i++): ?><i class="bi bi-star<?= $i <= $rv->rating ? '-fill' : '' ?>" style="color:<?= $i <= $rv->rating ? 'var(--gold)' : 'var(--gray-300)' ?>"></i><?php endfor; ?></span>
                            </div>
                            <?php if ($rv->comment): ?>
                                <p class="mb-0 mt-1"><?= htmlspecialchars($rv->comment) ?></p>
                            <?php endif; ?>
                            <small class="text-muted"><?= date('d M Y', strtotime($rv->created_at)) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
