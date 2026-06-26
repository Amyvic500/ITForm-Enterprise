<?php
namespace App\Models;

use App\utils\Database;

class ApprovalModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPendingApprovals($userId)
    {
        $sql = "SELECT * FROM tbl_approvals WHERE approver_id = ? AND status = 'pending' ORDER BY created_at DESC";
        return $this->db->select($sql, [$userId]);
    }

    public function getApprovalById($id)
    {
        $sql = "SELECT * FROM tbl_approvals WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }

    public function createApproval($data)
    {
        $sql = "INSERT INTO tbl_approvals (request_id, approver_id, status, created_at) VALUES (?, ?, ?, GETDATE())";
        return $this->db->query($sql, [$data['request_id'], $data['approver_id'], 'pending']);
    }

    public function updateApprovalStatus($id, $status, $comments = null)
    {
        $sql = "UPDATE tbl_approvals SET status = ?, comments = ?, updated_at = GETDATE() WHERE id = ?";
        return $this->db->query($sql, [$status, $comments, $id]);
    }
}
?>