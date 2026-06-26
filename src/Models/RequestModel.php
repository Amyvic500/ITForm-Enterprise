<?php
namespace App\Models;

use App\utils\Database;

class RequestModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getRequestById($id)
    {
        $sql = "SELECT * FROM tbl_requests WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }

    public function getAllRequests()
    {
        $sql = "SELECT * FROM tbl_requests ORDER BY created_at DESC";
        return $this->db->select($sql);
    }

    public function createRequest($data)
    {
        $sql = "INSERT INTO tbl_requests (user_id, title, description, created_at) VALUES (?, ?, ?, GETDATE())";
        return $this->db->query($sql, [$data['user_id'], $data['title'], $data['description']]);
    }

    public function updateRequestStatus($id, $status)
    {
        $sql = "UPDATE tbl_requests SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
}
?>