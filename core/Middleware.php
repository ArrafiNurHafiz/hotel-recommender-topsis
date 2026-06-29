<?php
class GuestMiddleware
{
    public function handle(): void
    {
        if (Auth::check()) {
            $role = Auth::role();
            $redirect = match ($role) {
                'super_admin' => '/super-admin/dashboard',
                'admin_hotel' => '/admin/dashboard',
                default       => '/',
            };
            header("Location: $redirect");
            exit;
        }
    }
}

class UserMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
    }
}

class AdminHotelMiddleware
{
    public function handle(): void
    {
        if (!Auth::check() || !Auth::is('admin_hotel')) {
            header('Location: /admin/login');
            exit;
        }
    }
}

class SuperAdminMiddleware
{
    public function handle(): void
    {
        if (!Auth::check() || !Auth::is('super_admin')) {
            header('Location: /super-admin/login');
            exit;
        }
    }
}
