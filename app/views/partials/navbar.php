<?php $user = Auth::user(); $role = Auth::role(); ?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top glass-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-building me-1"></i>HotelRecom
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/hotels">Hotel</a></li>
                <li class="nav-item"><a class="nav-link" href="/recommendations">Rekomendasi</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if ($user): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($user->name) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($role === 'user'): ?>
                                <li><a class="dropdown-item" href="/my-bookings">Booking Saya</a></li>
                            <?php elseif ($role === 'admin_hotel'): ?>
                                <li><a class="dropdown-item" href="/admin/dashboard">Dashboard Admin</a></li>
                            <?php elseif ($role === 'super_admin'): ?>
                                <li><a class="dropdown-item" href="/super-admin/dashboard">Dashboard Super Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                        </ul>
                    </li>
                    <form id="logout-form" action="/logout" method="POST" style="display:none"></form>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
