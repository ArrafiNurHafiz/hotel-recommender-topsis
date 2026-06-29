<div class="container py-5">
    <h2 class="fw-bold text-center mb-5">Bagaimana Cara Kerjanya</h2>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <div class="step-number">1</div>
                    <h6 class="fw-bold mt-3">Daftar & Login</h6>
                    <p class="text-muted small">Buat akun dan lengkapi profil Anda untuk mulai menggunakan fitur rekomendasi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <div class="step-number">2</div>
                    <h6 class="fw-bold mt-3">Masukkan Lokasi</h6>
                    <p class="text-muted small">Tentukan lokasi Anda untuk menghitung jarak ke setiap hotel secara akurat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <div class="step-number">3</div>
                    <h6 class="fw-bold mt-3">Dapatkan Rekomendasi</h6>
                    <p class="text-muted small">Sistem akan merangking hotel menggunakan metode Hybrid Entropy-TOPSIS berdasarkan 5 kriteria.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <div class="step-number">4</div>
                    <h6 class="fw-bold mt-3">Booking & Review</h6>
                    <p class="text-muted small">Booking kamar, nikmati menginap, dan berikan review setelah check-out.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-5">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Detail Metode Hybrid Entropy-TOPSIS</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>Harga</strong> <span class="text-muted small">— Semakin murah semakin baik (cost)</span></span>
                    <span class="badge bg-navy text-gold">Cost</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>Rating</strong> <span class="text-muted small">— Semakin tinggi rating semakin baik (benefit)</span></span>
                    <span class="badge bg-success">Benefit</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>Fasilitas</strong> <span class="text-muted small">— Semakin banyak fasilitas semakin baik (benefit)</span></span>
                    <span class="badge bg-success">Benefit</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>Jarak</strong> <span class="text-muted small">— Semakin dekat semakin baik (cost)</span></span>
                    <span class="badge bg-navy text-gold">Cost</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>Tingkat Keramaian</strong> <span class="text-muted small">— Semakin sepi semakin baik (cost)</span></span>
                    <span class="badge bg-navy text-gold">Cost</span>
                </li>
            </ul>
            <p class="text-muted small mt-3 mb-0">Bobot setiap kriteria dihitung secara otomatis menggunakan metode Entropy, memastikan penilaian yang objektif dan adaptif terhadap data yang ada.</p>
        </div>
    </div>
</div>
