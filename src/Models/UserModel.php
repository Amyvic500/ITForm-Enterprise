<?php
namespace App\Models;

class UserModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new \PDO('sqlsrv:Server=IT-VICTORIA\SQLEXPRESS;Database=ITForm_DB', 'itform_user', 'ITFORMdb2026@');
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateLastLogin($userId, $timestamp)
    {
        $stmt = $this->db->prepare("UPDATE tbl_users SET last_login = ? WHERE user_id = ?");
        return $stmt->execute([$timestamp, $userId]);
    }
}
