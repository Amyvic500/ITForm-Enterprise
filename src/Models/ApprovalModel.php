<?php
namespace App\Models;

class ApprovalModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new \PDO('sqlsrv:Server=IT-VICTORIA\SQLEXPRESS;Database=ITForm_DB', 'itform_user', 'ITFORMdb2026@');
    }

    public function getApprovalRoute($requestTypeId, $workerTypeId, $entityId = null, $departmentId = null)
    {
        $query = "SELECT * FROM tbl_approval_routes WHERE request_type_id = ? AND worker_type_id = ? AND is_active = 1 ORDER BY stage_number ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$requestTypeId, $workerTypeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPendingApprovalsForUser($userId)
    {
        $query = "
            SELECT DISTINCT 
                ri.item_id, r.request_id, r.request_number, u.full_name as requestor_name,
                s.software_name, al.level_name, ri.justification, ri.item_status,
                r.created_at, ah.approval_stage
            FROM tbl_request_items ri
            JOIN tbl_requests r ON ri.request_id = r.request_id
            JOIN tbl_users u ON r.requestor_id = u.user_id
            JOIN tbl_software s ON ri.software_id = s.software_id
            JOIN tbl_access_levels al ON ri.access_level_id = al.level_id
            JOIN tbl_approval_history ah ON ri.item_id = ah.item_id
            WHERE ah.assigned_approver_id = ? AND ah.status = 'PENDING' AND ri.item_status = 'PENDING'
            ORDER BY r.created_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createApproval($itemId, $approverId, $stage, $action, $comments = null)
    {
        $query = "INSERT INTO tbl_approvals (item_id, approver_id, approval_stage, approval_action, approval_comments, decision_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$itemId, $approverId, $stage, $action, $comments, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    }

    public function updateItemStatus($itemId, $status)
    {
        $query = "UPDATE tbl_request_items SET item_status = ?, updated_at = ? WHERE item_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, date('Y-m-d H:i:s'), $itemId]);
    }

    public function getApprovalHistory($itemId)
    {
        $query = "SELECT ah.*, u.full_name FROM tbl_approval_history ah LEFT JOIN tbl_users u ON ah.actual_approver_id = u.user_id WHERE ah.item_id = ? ORDER BY ah.approval_stage ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$itemId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createApprovalHistory($itemId, $stage, $expectedRoleId, $assignedApproverId = null)
    {
        $query = "INSERT INTO tbl_approval_history (item_id, approval_stage, expected_approver_role_id, assigned_approver_id, status, assigned_at) VALUES (?, ?, ?, ?, 'PENDING', ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$itemId, $stage, $expectedRoleId, $assignedApproverId, date('Y-m-d H:i:s')]);
    }

    public function updateApprovalHistory($historyId, $actualApproverId, $status)
    {
        $query = "UPDATE tbl_approval_history SET actual_approver_id = ?, status = ?, completed_at = ? WHERE history_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$actualApproverId, $status, date('Y-m-d H:i:s'), $historyId]);
    }

    public function getNextApprovalStage($itemId)
    {
        $query = "SELECT MAX(approval_stage) as current_stage FROM tbl_approval_history WHERE item_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$itemId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return ($result['current_stage'] ?? 0) + 1;
    }

    public function getApprovalsByItem($itemId)
    {
        $query = "SELECT a.*, u.full_name FROM tbl_approvals a JOIN tbl_users u ON a.approver_id = u.user_id WHERE a.item_id = ? ORDER BY a.approval_stage DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$itemId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRoleMembers($roleId)
    {
        $query = "SELECT user_id, full_name, email FROM tbl_users WHERE role_id = ? AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
