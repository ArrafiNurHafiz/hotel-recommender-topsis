<section class="hero-section">
    <div class="hero-bg">
        <video autoplay muted loop playsinline poster="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1400&q=80">
            <source src="https://cdn.pixabay.com/video/2023/02/17/151054-800027519_large.mp4" type="video/mp4">
        </video>
    </div>
    <div class="hero-pattern"></div>
    <div class="container">
        <div class="row align-items-center" style="min-height:75vh">
            <div class="col-lg-7">
                <div class="hero-accent">
                    <span class="hero-accent-line"></span>
                    <span class="hero-accent-text">Hybrid Entropy-TOPSIS</span>
                </div>
                <h1 class="hero-title">
                    Temukan Hotel<br><span class="text-gold">Terbaik</span> untuk Anda
                </h1>
                <p class="hero-subtitle">
                    Sistem rekomendasi hotel cerdas berbasis Hybrid Entropy-TOPSIS.
                    Dapatkan rekomendasi hotel yang sesuai dengan preferensi Anda.
                </p>
                <div class="d-flex gap-3">
                    <a href="/hotels" class="btn btn-gold btn-lg px-4">Cari Hotel</a>
                    <a href="/recommendations" class="btn btn-outline-gold btn-lg px-4">Rekomendasi</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold font-serif" style="font-size:2rem">Hotel Tersedia</h2>
            <p class="text-muted">Beberapa hotel yang sudah terverifikasi di sistem kami</p>
        </div>
        <?php if (empty($hotels)): ?>
            <div class="text-center py-5">
                <div class="empty-state-illustration"><i class="bi bi-building"></i></div>
                <p class="text-muted">Belum ada hotel tersedia</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($hotels as $h): ?>
                    <div class="col-md-4">
                        <a href="/hotels/<?= $h->id ?>" class="text-decoration-none">
                            <div class="card hotel-card">
                                <div class="overflow-hidden">
                                    <img src="<?= !empty($h->image) ? htmlspecialchars($h->image) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80' ?>" class="card-img-top" alt="<?= htmlspecialchars($h->name) ?>">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-dark"><?= htmlspecialchars($h->name) ?></h5>
                                    <p class="card-text text-muted small">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars(substr($h->address, 0, 50)) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-gold">Rp <?= number_format($h->price_start) ?>+/malam</span>
                                        <span class="rating-badge"><i class="bi bi-star-fill"></i> <?= $h->rating_avg ?: '-' ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="/hotels" class="btn btn-outline-gold">Lihat Semua Hotel</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold font-serif" style="font-size:2rem">Kenapa HotelRecom?</h2>
            <p class="text-muted">Tiga keunggulan utama sistem rekomendasi kami</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-cpu"></i></div>
                    <h5>Hybrid Entropy-TOPSIS</h5>
                    <p>Algoritma cerdas untuk perangkingan hotel berdasarkan banyak kriteria secara objektif.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-chat-square-quote"></i></div>
                    <h5>Live Review</h5>
                    <p>Review asli dari tamu yang sudah menginap. Dapatkan informasi akurat sebelum booking.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-people"></i></div>
                    <h5>Monitoring Keramaian</h5>
                    <p>Pantau tingkat okupansi hotel secara real-time untuk pengalaman menginap yang nyaman.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="cta-section">
            <h2 class="fw-bold font-serif text-white" style="font-size:2rem">Siap Mencari Hotel?</h2>
            <p class="text-white-50 mb-4" style="max-width:500px;margin-inline:auto">Gunakan sistem rekomendasi kami untuk menemukan hotel yang tepat sesuai preferensi Anda</p>
            <a href="/recommendations" class="btn btn-gold btn-lg px-5">Lihat Rekomendasi</a>
        </div>
    </div>
</section>
