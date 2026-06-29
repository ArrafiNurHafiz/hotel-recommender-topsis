<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4">Daftar Akun</h4>
                    <?php if (isset($errors)): ?>
                        <?php foreach ($errors as $e): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Daftar</button>
                    </form>
                    <p class="text-center mt-3 mb-0">
                        <small>Sudah punya akun? <a href="/login">Login</a></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
