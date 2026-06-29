<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Middleware.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/routes.php';

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$uri    = $_SERVER['REQUEST_URI'];

Router::dispatch($method, $uri);
