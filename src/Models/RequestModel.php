<?php
namespace App\Models;

class RequestModel
{
    protected $db;
    protected $table = 'tbl_requests';

    public function __construct()
    {
        $this->db = new \PDO('sqlsrv:Server=IT-VICTORIA\SQLEXPRESS;Database=ITForm_DB', 'itform_user', 'ITFORMdb2026@');
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table} (request_number, requestor_id, request_type_id, department_id, entity_id, location_id, current_stage, overall_status, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, 'PENDING', ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['request_number'],
            $data['requestor_id'],
            $data['request_type_id'],
            $data['department_id'],
            $data['entity_id'],
            $data['location_id'],
            date('Y-m-d H:i:s')
        ]);
    }

    public function findById($requestId)
    {
        $query = "SELECT r.*, rt.type_name, u.full_name as requestor_name, d.department_name FROM {$this->table} r JOIN tbl_request_types rt ON r.request_type_id = rt.type_id JOIN tbl_users u ON r.requestor_id = u.user_id JOIN tbl_departments d ON r.department_id = d.department_id WHERE r.request_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$requestId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByRequestor($userId, $limit = 20)
    {
        $query = "SELECT r.*, rt.type_name FROM {$this->table} r JOIN tbl_request_types rt ON r.request_type_id = rt.type_id WHERE r.requestor_id = ? ORDER BY r.created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateStatus($requestId, $status)
    {
        $query = "UPDATE {$this->table} SET overall_status = ?, updated_at = ? WHERE request_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, date('Y-m-d H:i:s'), $requestId]);
    }

    public function submit($requestId)
    {
        $query = "UPDATE {$this->table} SET overall_status = 'IN_PROGRESS', submitted_at = ?, current_stage = 1 WHERE request_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([date('Y-m-d H:i:s'), $requestId]);
    }

    public function addRequestItem($requestId, $softwareId, $accessLevelId, $justification)
    {
        $query = "INSERT INTO tbl_request_items (request_id, software_id, access_level_id, justification, item_status, created_at) VALUES (?, ?, ?, ?, 'PENDING', ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$requestId, $softwareId, $accessLevelId, $justification, date('Y-m-d H:i:s')]);
    }

    public function getRequestItems($requestId)
    {
        $query = "SELECT ri.*, s.software_name, al.level_name FROM tbl_request_items ri JOIN tbl_software s ON ri.software_id = s.software_id JOIN tbl_access_levels al ON ri.access_level_id = al.level_id WHERE ri.request_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$requestId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function generateRequestNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = $this->db->query("SELECT COUNT(*) as cnt FROM {$this->table} WHERE request_number LIKE '{$year}{$month}%'")->fetch(\PDO::FETCH_ASSOC);
        $seq = ($count['cnt'] ?? 0) + 1;
        return $year . $month . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
