<?php
class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(object $user): void
    {
        self::start();
        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
    }

    public static function logout(): void
    {
        self::start();
        session_destroy();
    }

    public static function user(): ?object
    {
        self::start();
        if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) return null;
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        return $user ?: null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user ? (int)$user->id : null;
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user ? $user->role : null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function is(string $role): bool
    {
        return self::role() === $role;
    }
}
