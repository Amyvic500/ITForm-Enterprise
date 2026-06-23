<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(__FILE__));
define('VIEW_PATH', BASE_PATH . '/views');
define('BASE_URL', 'http://localhost/itform');

// Manual autoloader
spl_autoload_register(function($class) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = BASE_PATH . '/src/' . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

use App\Utils\Router;
use App\Controllers\AuthController;

$router = new Router();

$router->post('/itform/auth/login', AuthController::class, 'login');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($requestMethod, $requestPath);
