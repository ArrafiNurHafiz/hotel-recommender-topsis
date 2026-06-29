<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4">Login</h4>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Login</button>
                    </form>
                    <p class="text-center mt-3 mb-0">
                        <small>Belum punya akun? <a href="/register">Daftar</a></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
