<?php
namespace App\Models;

use App\utils\Database;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM tbl_users WHERE email = ?";
        return $this->db->selectOne($sql, [$email]);
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM tbl_users WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM tbl_users";
        return $this->db->select($sql);
    }
}
?>