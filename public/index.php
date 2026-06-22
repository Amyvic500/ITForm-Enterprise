<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(dirname(__FILE__)));
define('VIEW_PATH', BASE_PATH . '/views');
define('BASE_URL', 'http://localhost/itform');

require_once BASE_PATH . '/vendor/autoload.php';

use App\Utils\Router;
use App\Controllers\AuthController;
use App\Controllers\RequestController;
use App\Controllers\ApprovalController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;

$router = new Router();

// Auth routes
$router->post('/itform/auth/login', AuthController::class, 'login');
$router->get('/itform/auth/logout', AuthController::class, 'logout');

// Request routes
$router->post('/itform/requests/create', RequestController::class, 'create');
$router->post('/itform/requests/add-software', RequestController::class, 'addSoftware');
$router->post('/itform/requests/submit', RequestController::class, 'submit');
$router->get('/itform/requests/view', RequestController::class, 'view');
$router->get('/itform/requests/my-requests', RequestController::class, 'myRequests');

// Approval routes
$router->get('/itform/approvals/pending', ApprovalController::class, 'pending');
$router->post('/itform/approvals/approve', ApprovalController::class, 'approve');
$router->post('/itform/approvals/reject', ApprovalController::class, 'reject');
$router->post('/itform/approvals/escalate', ApprovalController::class, 'escalate');
$router->get('/itform/approvals/history', ApprovalController::class, 'history');

// Dashboard routes
$router->get('/itform/dashboard', DashboardController::class, 'index');
$router->get('/itform/dashboard/stats', DashboardController::class, 'getStats');

// Admin routes
$router->get('/itform/admin/users', AdminController::class, 'users');
$router->get('/itform/admin/user', AdminController::class, 'getUser');
$router->post('/itform/admin/user/update', AdminController::class, 'updateUser');
$router->post('/itform/admin/user/deactivate', AdminController::class, 'deactivateUser');
$router->post('/itform/admin/settings', AdminController::class, 'updateSettings');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($requestMethod, $requestPath);
