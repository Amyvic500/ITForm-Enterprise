<?php
namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function authenticate($email, $password)
    {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return ['success' => false];
        }
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false];
        }
        return ['success' => true, 'user' => $user];
    }

    public function updateLastLogin($userId)
    {
        return $this->userModel->updateLastLogin($userId, date('Y-m-d H:i:s'));
    }
}
