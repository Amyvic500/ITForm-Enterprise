<?php
namespace App\Controllers;

class BaseController
{
    protected $userId = null;
    protected $userRole = null;
    protected $userData = null;
    protected $requestMethod = null;
    protected $requestBody = null;

    public function __construct()
    {
        session_start();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->userId = $_SESSION['user_id'] ?? null;
        $this->userRole = $_SESSION['user_role'] ?? null;
        $this->userData = $_SESSION['user_data'] ?? null;

        if (in_array($this->requestMethod, ['POST', 'PUT', 'PATCH'])) {
            $this->requestBody = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        }
    }

    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin('Session expired. Please login again.');
        }
    }

    protected function isAuthenticated()
    {
        return !is_null($this->userId) && !is_null($this->userRole);
    }

    protected function getParam($key, $default = null)
    {
        if ($this->requestMethod === 'GET') {
            return $_GET[$key] ?? $default;
        }
        return $this->requestBody[$key] ?? $default;
    }

    protected function validateRequired($params)
    {
        $missing = [];
        foreach ($params as $param) {
            if (empty($this->getParam($param))) {
                $missing[] = $param;
            }
        }
        if (!empty($missing)) {
            $this->respondWithError(400, 'Missing: ' . implode(', ', $missing));
            exit;
        }
    }

    protected function respondSuccess($data = null, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'code' => $statusCode, 'data' => $data]);
        exit;
    }

    protected function respondWithError($statusCode, $message)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'code' => $statusCode, 'message' => $message]);
        exit;
    }

    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    protected function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
