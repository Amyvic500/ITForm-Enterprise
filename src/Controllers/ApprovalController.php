<?php
namespace App\Controllers;

use App\Services\ApprovalService;
use App\Models\ApprovalModel;

class ApprovalController extends BaseController
{
    protected $approvalService;
    protected $approvalModel;

    public function __construct()
    {
        parent::__construct();
        $this->approvalService = new ApprovalService();
        $this->approvalModel = new ApprovalModel();
    }

    public function pending()
    {
        $this->requireAuth();
        $this->requirePermission('APPROVE_REQUESTS');

        $result = $this->approvalService->getPendingApprovalsForUser($this->userId);

        $this->respondSuccess($result, 200);
    }

    public function approve()
    {
        $this->requireAuth();
        $this->requirePermission('APPROVE_REQUESTS');

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $itemId = $this->getParam('item_id');
        $stage = $this->getParam('stage');
        $this->validateRequired(['item_id', 'stage']);

        $result = $this->approvalService->approveItem($itemId, $this->userId, $stage);

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('ITEM_APPROVED', 'tbl_request_items', $itemId);

        $this->respondSuccess(['message' => $result['message']], 200);
    }

    public function reject()
    {
        $this->requireAuth();
        $this->requirePermission('REJECT_REQUESTS');

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $itemId = $this->getParam('item_id');
        $stage = $this->getParam('stage');
        $reason = $this->sanitize($this->getParam('reason'));
        $this->validateRequired(['item_id', 'stage', 'reason']);

        $result = $this->approvalService->rejectItem($itemId, $this->userId, $stage, $reason);

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('ITEM_REJECTED', 'tbl_request_items', $itemId, null, ['reason' => $reason]);

        $this->respondSuccess(['message' => $result['message']], 200);
    }

    public function escalate()
    {
        $this->requireAuth();

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $itemId = $this->getParam('item_id');
        $reason = $this->sanitize($this->getParam('reason'));
        $this->validateRequired(['item_id', 'reason']);

        $result = $this->approvalService->escalateItem($itemId, $this->userId, $reason);

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('ITEM_ESCALATED', 'tbl_request_items', $itemId, null, ['reason' => $reason]);

        $this->respondSuccess(['message' => $result['message']], 200);
    }

    public function history()
    {
        $this->requireAuth();

        $itemId = $this->getParam('item_id');
        $this->validateRequired(['item_id']);

        $result = $this->approvalService->getApprovalHistory($itemId);

        $this->respondSuccess($result, 200);
    }

    public function list()
    {
        $this->requireAuth();
        $this->requirePermission('APPROVE_REQUESTS');

        include VIEW_PATH . '/approvals/list.php';
    }
}
