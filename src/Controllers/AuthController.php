<?php
namespace App\Controllers;

use App\Services\AuthService;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function loginForm()
    {
        include BASE_PATH . '/views/auth/login.php';
    }

    public function login()
    {
        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
            return;
        }

        $email = $this->sanitize($this->getParam('email'));
        $password = $this->getParam('password');

        if (!$email || !$password) {
            $this->respondWithError(400, 'Email and password required');
            return;
        }

        $result = $this->authService->authenticate($email, $password);
        
        if (!$result['success']) {
            $this->respondWithError(401, $result['message']);
            return;
        }

        $_SESSION['user_id'] = $result['user']['user_id'];
        $_SESSION['user_email'] = $result['user']['email'];
        $_SESSION['user_full_name'] = $result['user']['full_name'];

        $this->respondSuccess(['message' => 'Login successful', 'user' => $result['user']]);
    }

    public function logout()
    {
        session_destroy();
        $this->respondSuccess(['message' => 'Logged out']);
    }
}
?>