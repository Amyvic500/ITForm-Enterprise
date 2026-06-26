<?php
namespace App\Services;

use App\Models\ApprovalModel;

class ApprovalService
{
    protected $approvalModel;

    public function __construct()
    {
        $this->approvalModel = new ApprovalModel();
    }

    public function getApprovalRoute($requestTypeId, $workerTypeId, $entityId = null, $departmentId = null)
    {
        return $this->approvalModel->getApprovalRoute($requestTypeId, $workerTypeId, $entityId, $departmentId);
    }

    public function getPendingApprovalsForUser($userId)
    {
        $approvals = $this->approvalModel->getPendingApprovalsForUser($userId);
        return [
            'success' => true,
            'approvals' => $approvals,
            'count' => count($approvals)
        ];
    }

    public function approveItem($itemId, $approverId, $stage, $comments = null)
    {
        $approval = $this->approvalModel->createApproval($itemId, $approverId, $stage, 'APPROVED', $comments);

        if (!$approval) {
            return ['success' => false, 'message' => 'Failed to create approval'];
        }

        $this->approvalModel->updateApprovalHistory($itemId, $approverId, 'APPROVED');

        return ['success' => true, 'message' => 'Item approved'];
    }

    public function rejectItem($itemId, $approverId, $stage, $comments)
    {
        if (empty($comments)) {
            return ['success' => false, 'message' => 'Rejection reason required'];
        }

        $approval = $this->approvalModel->createApproval($itemId, $approverId, $stage, 'REJECTED', $comments);

        if (!$approval) {
            return ['success' => false, 'message' => 'Failed to create rejection'];
        }

        $this->approvalModel->updateItemStatus($itemId, 'REJECTED');

        return ['success' => true, 'message' => 'Item rejected'];
    }

    public function escalateItem($itemId, $escalatedBy, $reason)
    {
        if (empty($reason)) {
            return ['success' => false, 'message' => 'Escalation reason required'];
        }

        $approval = $this->approvalModel->createApproval($itemId, $escalatedBy, null, 'ESCALATED', $reason);

        if (!$approval) {
            return ['success' => false, 'message' => 'Failed to escalate'];
        }

        return ['success' => true, 'message' => 'Item escalated'];
    }

    public function getApprovalHistory($itemId)
    {
        $history = $this->approvalModel->getApprovalHistory($itemId);

        return [
            'success' => true,
            'history' => $history
        ];
    }

    public function initiateApprovalWorkflow($itemId, $requestTypeId, $workerTypeId)
    {
        $route = $this->getApprovalRoute($requestTypeId, $workerTypeId);

        if (empty($route)) {
            return ['success' => false, 'message' => 'No approval route configured'];
        }

        foreach ($route as $stage) {
            $this->approvalModel->createApprovalHistory($itemId, $stage['stage_number'], $stage['approver_role_id']);
        }

        return ['success' => true, 'message' => 'Approval workflow initiated', 'stages' => count($route)];
    }
}
