<?php
namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function authenticate($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }

        // Hash the provided password and compare
        $hash = hash('sha256', $password);
        $storedHash = strtolower($user['password_hash']);

        if ($hash !== $storedHash) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function updateLastLogin($userId)
    {
        $sql = "UPDATE tbl_users SET last_login = GETDATE() WHERE user_id = ?";
        // Execute query
    }
}
?>