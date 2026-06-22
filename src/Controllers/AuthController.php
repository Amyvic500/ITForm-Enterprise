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

    public function login()
    {
        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $email = $this->sanitize($this->getParam('email'));
        $password = $this->getParam('password');
        $this->validateRequired(['email', 'password']);

        if (!$this->validateEmail($email)) {
            $this->respondWithError(400, 'Invalid email');
        }

        $result = $this->authService->authenticate($email, $password);

        if (!$result['success']) {
            $this->respondWithError(401, 'Invalid credentials');
        }

        $user = $result['user'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_full_name'] = $user['full_name'];

        $this->authService->updateLastLogin($user['user_id']);

        $this->respondSuccess(['message' => 'Login successful', 'user' => $user]);
    }

    public function logout()
    {
        $this->requireAuth();
        session_destroy();
        $this->respondSuccess(['message' => 'Logged out']);
    }
}
