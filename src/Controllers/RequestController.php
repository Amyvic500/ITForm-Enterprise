<?php
namespace App\Controllers;

use App\Services\RequestService;
use App\Models\RequestModel;

class RequestController extends BaseController
{
    protected $requestService;
    protected $requestModel;

    public function __construct()
    {
        parent::__construct();
        $this->requestService = new RequestService();
        $this->requestModel = new RequestModel();
    }

    public function showCreate()
    {
        $this->requireAuth();
        include VIEW_PATH . '/requests/create.php';
    }

    public function create()
    {
        $this->requireAuth();

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $requestTypeId = $this->getParam('request_type_id');
        $this->validateRequired(['request_type_id']);

        $user = $this->getCurrentUser();

        $result = $this->requestService->createRequest(
            $this->userId,
            $requestTypeId,
            $user['department_id'],
            $user['entity_id'],
            $user['location_id']
        );

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('REQUEST_CREATED', 'tbl_requests', null, null, ['request_number' => $result['request_number']]);

        $this->respondSuccess([
            'message' => 'Request created',
            'request_number' => $result['request_number']
        ], 201);
    }

    public function addSoftware()
    {
        $this->requireAuth();

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $requestId = $this->getParam('request_id');
        $softwareId = $this->getParam('software_id');
        $accessLevelId = $this->getParam('access_level_id');
        $justification = $this->sanitize($this->getParam('justification'));

        $this->validateRequired(['request_id', 'software_id', 'access_level_id', 'justification']);

        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['requestor_id'] != $this->userId) {
            $this->respondWithError(403, 'Unauthorized');
        }

        $result = $this->requestService->addRequestItem($requestId, $softwareId, $accessLevelId, $justification);

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('REQUEST_ITEM_ADDED', 'tbl_request_items', $requestId);

        $this->respondSuccess(['message' => $result['message']], 201);
    }

    public function submit()
    {
        $this->requireAuth();

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $requestId = $this->getParam('request_id');
        $this->validateRequired(['request_id']);

        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['requestor_id'] != $this->userId) {
            $this->respondWithError(403, 'Unauthorized');
        }

        $result = $this->requestService->submitRequest($requestId);

        if (!$result['success']) {
            $this->respondWithError(400, $result['message']);
        }

        $this->logAudit('REQUEST_SUBMITTED', 'tbl_requests', $requestId);

        $this->respondSuccess(['message' => $result['message']], 200);
    }

    public function view()
    {
        $this->requireAuth();

        $requestId = $this->getParam('id');
        $this->validateRequired(['id']);

        $result = $this->requestService->getRequestStatus($requestId);

        if (!$result['success']) {
            $this->respondWithError(404, $result['message']);
        }

        $request = $result['request'];
        if ($request['requestor_id'] != $this->userId && $this->userRole !== 'Super Admin') {
            $this->respondWithError(403, 'Unauthorized');
        }

        $this->respondSuccess([
            'request' => $result['request'],
            'items' => $result['items']
        ], 200);
    }

    public function myRequests()
    {
        $this->requireAuth();

        $result = $this->requestService->getUserRequests($this->userId);

        $this->respondSuccess([
            'requests' => $result['requests'],
            'count' => $result['count']
        ], 200);
    }

    public function list()
    {
        $this->requireAuth();
        $this->requirePermission('VIEW_ALL_REQUESTS');

        include VIEW_PATH . '/requests/list.php';
    }
}
