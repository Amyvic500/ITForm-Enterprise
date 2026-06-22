<?php
namespace App\Services;

use App\Models\RequestModel;

class RequestService
{
    protected $requestModel;

    public function __construct()
    {
        $this->requestModel = new RequestModel();
    }

    public function createRequest($userId, $requestTypeId, $departmentId, $entityId, $locationId)
    {
        $requestNumber = $this->requestModel->generateRequestNumber();

        $result = $this->requestModel->create([
            'request_number' => $requestNumber,
            'requestor_id' => $userId,
            'request_type_id' => $requestTypeId,
            'department_id' => $departmentId,
            'entity_id' => $entityId,
            'location_id' => $locationId
        ]);

        if (!$result) {
            return ['success' => false, 'message' => 'Failed to create request'];
        }

        return [
            'success' => true,
            'request_number' => $requestNumber,
            'message' => 'Request created successfully'
        ];
    }

    public function addRequestItem($requestId, $softwareId, $accessLevelId, $justification)
    {
        $result = $this->requestModel->addRequestItem($requestId, $softwareId, $accessLevelId, $justification);

        if (!$result) {
            return ['success' => false, 'message' => 'Failed to add software'];
        }

        return ['success' => true, 'message' => 'Software added to request'];
    }

    public function submitRequest($requestId)
    {
        $request = $this->requestModel->findById($requestId);

        if (!$request) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        if ($request['overall_status'] !== 'PENDING') {
            return ['success' => false, 'message' => 'Only pending requests can be submitted'];
        }

        $items = $this->requestModel->getRequestItems($requestId);
        if (empty($items)) {
            return ['success' => false, 'message' => 'Request must have at least one software item'];
        }

        $result = $this->requestModel->submit($requestId);

        if (!$result) {
            return ['success' => false, 'message' => 'Failed to submit request'];
        }

        return ['success' => true, 'message' => 'Request submitted for approval'];
    }

    public function getRequestStatus($requestId)
    {
        $request = $this->requestModel->findById($requestId);

        if (!$request) {
            return ['success' => false, 'message' => 'Request not found'];
        }

        $items = $this->requestModel->getRequestItems($requestId);

        return [
            'success' => true,
            'request' => $request,
            'items' => $items
        ];
    }

    public function getUserRequests($userId)
    {
        $requests = $this->requestModel->getByRequestor($userId);

        return [
            'success' => true,
            'requests' => $requests,
            'count' => count($requests)
        ];
    }
}
