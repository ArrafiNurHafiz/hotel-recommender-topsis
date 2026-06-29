<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card auth-card" style="border-color:var(--gold);">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4"><i class="bi bi-shield-lock"></i> Login Super Admin</h4>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/super-admin/login">
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
                </div>
            </div>
        </div>
    </div>
</div>
