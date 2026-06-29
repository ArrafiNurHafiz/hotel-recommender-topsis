<?php
class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = Database::fetch("SELECT * FROM users WHERE email = ?", [$email]);
        if (!$user || !password_verify($password, $user->password)) {
            $this->view('auth/login', ['title' => 'Login', 'error' => 'Email atau password salah']);
            return;
        }

        if ($user->role === 'super_admin') {
            $this->view('auth/login', ['title' => 'Login', 'error' => 'Gunakan halaman login Super Admin']);
            return;
        }
        if ($user->role === 'admin_hotel') {
            $this->view('auth/login', ['title' => 'Login', 'error' => 'Gunakan halaman login Admin Hotel']);
            return;
        }

        Auth::login($user);
        $this->redirect('/');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['title' => 'Register']);
    }

    public function register(): void
    {
        $name     = $_POST['name'] ?? '';
        $email    = $_POST['email'] ?? '';
        $phone    = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $errors = [];
        if (strlen($name) < 3) $errors[] = 'Nama minimal 3 karakter';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
        if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
        if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok';

        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) $errors[] = 'Email sudah terdaftar';

        if ($errors) {
            $this->view('auth/register', ['title' => 'Register', 'errors' => $errors]);
            return;
        }

        User::create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => 'user',
        ]);

        $this->redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }

    // ===== ADMIN LOGIN =====
    public function adminLoginForm(): void
    {
        $this->view('auth/admin-login', ['title' => 'Login Admin Hotel']);
    }

    public function adminLogin(): void
    {
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = Database::fetch("SELECT * FROM users WHERE email = ? AND role = 'admin_hotel'", [$email]);
        if (!$user || !password_verify($password, $user->password)) {
            $this->view('auth/admin-login', ['title' => 'Login Admin', 'error' => 'Email atau password salah']);
            return;
        }

        Auth::login($user);
        $this->redirect('/admin/dashboard');
    }

    // ===== SUPER ADMIN LOGIN =====
    public function superAdminLoginForm(): void
    {
        $this->view('auth/super-admin-login', ['title' => 'Login Super Admin']);
    }

    public function superAdminLogin(): void
    {
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = Database::fetch("SELECT * FROM users WHERE email = ? AND role = 'super_admin'", [$email]);
        if (!$user || !password_verify($password, $user->password)) {
            $this->view('auth/super-admin-login', ['title' => 'Login Super Admin', 'error' => 'Email atau password salah']);
            return;
        }

        Auth::login($user);
        $this->redirect('/super-admin/dashboard');
    }
}
