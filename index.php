use App\utils\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\RequestController;
use App\Controllers\ApprovalController;
use App\Controllers\PublicController;

$router = new Router();

// Public routes (no login required)
$router->get('/itform/', PublicController::class, 'requestForm');
$router->get('/itform', PublicController::class, 'requestForm');
$router->post('/itform/requests/submit', PublicController::class, 'createRequest');

// Admin auth routes
$router->get('/itform/admin/login', AuthController::class, 'loginForm');
$router->post('/itform/admin/login', AuthController::class, 'login');
$router->get('/itform/admin/logout', AuthController::class, 'logout');

// Admin dashboard routes (login required)
$router->get('/itform/dashboard', DashboardController::class, 'index');
$router->get('/itform/requests', RequestController::class, 'list');
$router->post('/itform/requests', RequestController::class, 'create');
$router->get('/itform/requests/create', RequestController::class, 'createForm');
$router->get('/itform/approvals', ApprovalController::class, 'pending');
$router->post('/itform/approvals', ApprovalController::class, 'approve');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($requestMethod, $requestPath);