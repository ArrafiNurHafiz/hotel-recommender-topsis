<?php
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . "/../app/views/$view.php";
        if (file_exists($viewPath)) {
            require_once __DIR__ . '/../app/views/layouts/main.php';
        } else {
            throw new Exception("View '$view' not found");
        }
    }

    protected function viewOnly(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../app/views/$view.php";
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function back(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
