&lt;?php
/**
 * ITForm Enterprise - Application Entry Point
 * 
 * All requests are routed through this file
 * Handles routing, middleware, and request processing
 */

// Define application base path
define('BASE_PATH', __DIR__ . '/..');
define('PUBLIC_PATH', __DIR__);
define('SRC_PATH', BASE_PATH . '/src');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', STORAGE_PATH . '/logs/error.log');

// Load environment variables
require_once BASE_PATH . '/src/utils/DotEnv.php';
$dotenv = new DotEnv(BASE_PATH);
$dotenv->load();

// Load configuration
$config = [
    'app' => require CONFIG_PATH . '/app.php',
    'database' => require CONFIG_PATH . '/database.php',
];

// Start session
session_name($config['app']['session']['name']);
session_set_cookie_params([
    'lifetime' => $config['app']['session']['lifetime'],
    'path' => '/',
    'domain' => '',
    'secure' => $config['app']['session']['cookie']['secure'],
    'httponly' => $config['app']['session']['cookie']['http_only'],
    'samesite' => $config['app']['session']['cookie']['same_site'],
]);
session_start();

// Load core classes
require_once SRC_PATH . '/utils/Database.php';
require_once SRC_PATH . '/utils/Router.php';
require_once SRC_PATH . '/middleware/AuthMiddleware.php';
require_once SRC_PATH . '/middleware/PermissionMiddleware.php';

// Initialize database connection
try {
    $dbConfig = $config['database']['connections'][$config['database']['default']];
    $db = new Database($dbConfig);
} catch(Exception $e) {
    if(php_sapi_name() === 'cli') {
        echo "Database Connection Error: " . $e->getMessage() . "\n";
    } else {
        http_response_code(500);
        die("The application encountered a database error. Please try again later.");
    }
    exit;
}

// Check if user is authenticated
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;
$user_email = $_SESSION['user_email'] ?? null;
$user_role = $_SESSION['user_role'] ?? null;

// Get requested route
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_url = '/itform';
$route = str_replace($base_url, '', $request_uri);
$route = trim($route, '/');

// Default route
if(empty($route)) {
    $route = $isAuthenticated ? 'dashboard' : 'auth/login';
}

// CORS headers for API (if enabled)
if(strpos($route, 'api/') === 0) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

// Determine which controller to load
$controller = null;
$action = null;

// Route mapping
$routes = [
    // Authentication routes
    'auth/login' => ['controller' => 'AuthController', 'action' => 'login', 'auth_required' => false],
    'auth/logout' => ['controller' => 'AuthController', 'action' => 'logout', 'auth_required' => true],
    'auth/register' => ['controller' => 'AuthController', 'action' => 'register', 'auth_required' => false],
    'auth/forgot-password' => ['controller' => 'AuthController', 'action' => 'forgotPassword', 'auth_required' => false],
    
    // Dashboard
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index', 'auth_required' => true],
    
    // Request management
    'requests' => ['controller' => 'RequestController', 'action' => 'index', 'auth_required' => true],
    'requests/create' => ['controller' => 'RequestController', 'action' => 'create', 'auth_required' => true],
    'requests/view' => ['controller' => 'RequestController', 'action' => 'view', 'auth_required' => true],
    'requests/submit' => ['controller' => 'RequestController', 'action' => 'submit', 'auth_required' => true],
    
    // Approval routes
    'approvals' => ['controller' => 'ApprovalController', 'action' => 'index', 'auth_required' => true],
    'approvals/pending' => ['controller' => 'ApprovalController', 'action' => 'pending', 'auth_required' => true],
    'approvals/approve' => ['controller' => 'ApprovalController', 'action' => 'approve', 'auth_required' => true],
    'approvals/reject' => ['controller' => 'ApprovalController', 'action' => 'reject', 'auth_required' => true],
    'approvals/history' => ['controller' => 'ApprovalController', 'action' => 'history', 'auth_required' => true],
    
    // Admin routes
    'admin' => ['controller' => 'AdminController', 'action' => 'dashboard', 'auth_required' => true, 'permission' => 'SYSTEM_CONFIG'],
    'admin/users' => ['controller' => 'AdminController', 'action' => 'manageUsers', 'auth_required' => true, 'permission' => 'MANAGE_USERS'],
    'admin/software' => ['controller' => 'AdminController', 'action' => 'manageSoftware', 'auth_required' => true, 'permission' => 'MANAGE_SOFTWARE'],
    'admin/departments' => ['controller' => 'AdminController', 'action' => 'manageDepartments', 'auth_required' => true, 'permission' => 'MANAGE_DEPARTMENTS'],
    'admin/entities' => ['controller' => 'AdminController', 'action' => 'manageEntities', 'auth_required' => true, 'permission' => 'MANAGE_ENTITIES'],
    'admin/routes' => ['controller' => 'AdminController', 'action' => 'configureRoutes', 'auth_required' => true, 'permission' => 'CONFIGURE_ROUTES'],
    'admin/settings' => ['controller' => 'AdminController', 'action' => 'systemSettings', 'auth_required' => true, 'permission' => 'SYSTEM_CONFIG'],
    
    // Reports
    'reports' => ['controller' => 'ReportController', 'action' => 'index', 'auth_required' => true, 'permission' => 'VIEW_REPORTS'],
    'reports/requests' => ['controller' => 'ReportController', 'action' => 'requestStatus', 'auth_required' => true, 'permission' => 'VIEW_REPORTS'],
    'reports/approvals' => ['controller' => 'ReportController', 'action' => 'approvalTimeline', 'auth_required' => true, 'permission' => 'VIEW_REPORTS'],
    'reports/audit' => ['controller' => 'ReportController', 'action' => 'auditLog', 'auth_required' => true, 'permission' => 'VIEW_AUDIT_LOGS'],
];

// Find matching route
$routeInfo = null;
foreach($routes as $pattern => $info) {
    if($route === $pattern || strpos($route, $pattern . '/') === 0) {
        $routeInfo = $info;
        break;
    }
}

// If no route found, show 404
if(!$routeInfo) {
    http_response_code(404);
    include PUBLIC_PATH . '/404.html';
    exit;
}

// Check authentication requirement
if($routeInfo['auth_required'] && !$isAuthenticated) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: ' . $base_url . '/auth/login');
    exit;
}

// Check permissions if required
if(isset($routeInfo['permission']) && $isAuthenticated) {
    $hasPermission = checkUserPermission($db, $user_id, $routeInfo['permission']);
    if(!$hasPermission) {
        http_response_code(403);
        include PUBLIC_PATH . '/403.html';
        exit;
    }
}

// Load controller
$controllerFile = SRC_PATH . '/controllers/' . $routeInfo['controller'] . '.php';
if(!file_exists($controllerFile)) {
    http_response_code(500);
    die("Controller not found: " . $routeInfo['controller']);
}

require_once $controllerFile;

// Instantiate and execute controller action
$controllerClass = $routeInfo['controller'];
$action = $routeInfo['action'];

if(!class_exists($controllerClass)) {
    http_response_code(500);
    die("Controller class not found: " . $controllerClass);
}

$controller = new $controllerClass($db, $config);

if(!method_exists($controller, $action)) {
    http_response_code(500);
    die("Action not found: " . $action);
}

// Execute action
ob_start();
$controller-&gt;$action();
$output = ob_get_clean();
echo $output;

// Helper function to check user permissions
function checkUserPermission($db, $user_id, $permission_code) {
    try {
        $sql = "
            SELECT COUNT(*) as count
            FROM tbl_role_permissions rp
            JOIN tbl_permissions p ON rp.permission_id = p.permission_id
            JOIN tbl_users u ON u.role_id = rp.role_id
            WHERE u.user_id = ? AND p.permission_code = ?
        ";
        $result = $db->query($sql, [$user_id, $permission_code]);
        $row = $result->fetch();
        return $row['count'] > 0;
    } catch(Exception $e) {
        return false;
    }
}

?&gt;
