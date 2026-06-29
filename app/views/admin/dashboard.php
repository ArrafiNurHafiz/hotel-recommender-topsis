<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h5 class="text-white mb-4"><i class="bi bi-building"></i> Admin Panel</h5>
            <a href="/admin/dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/hotels/edit"><i class="bi bi-building"></i> Profil Hotel</a>
            <a href="/admin/rooms"><i class="bi bi-door-open"></i> Kelola Kamar</a>
            <a href="/admin/bookings"><i class="bi bi-calendar-check"></i> Booking</a>
            <a href="/admin/reviews"><i class="bi bi-chat-square-text"></i> Review</a>
            <a href="/" class="mt-3"><i class="bi bi-arrow-left"></i> Ke Website</a>
        </div>
        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Dashboard Admin</h4>
            <?php if (!$hotel): ?>
                <div class="alert alert-warning">
                    Anda belum memiliki data hotel. <a href="/admin/hotels/edit">Lengkapi profil hotel</a>.
                </div>
            <?php else: ?>
                <?php if ($hotel->status === 'pending'): ?>
                    <div class="alert alert-info">Hotel Anda sedang menunggu verifikasi Super Admin.</div>
                <?php elseif ($hotel->status === 'rejected'): ?>
                    <div class="alert alert-danger">Hotel Anda ditolak. Hubungi Super Admin.</div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-door-open"></i></div>
                        <div class="number"><?= $stats->total_rooms ?></div>
                        <div class="text-muted small">Total Kamar</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-clock"></i></div>
                        <div class="number"><?= $stats->pending_bookings ?></div>
                        <div class="text-muted small">Booking Pending</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-chat-square-text"></i></div>
                        <div class="number"><?= $stats->total_reviews ?></div>
                        <div class="text-muted small">Review</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon"><i class="bi bi-people"></i></div>
                        <div class="number"><?= $stats->occupancy ?>%</div>
                        <div class="text-muted small">Okupansi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
