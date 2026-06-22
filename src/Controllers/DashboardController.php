<?php
namespace App\Controllers;

use App\Models\RequestModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected $requestModel;
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->requestModel = new RequestModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        $myRequests = $this->requestModel->getByRequestor($this->userId, 5);

        $stats = [
            'total_requests' => count($myRequests),
            'pending_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'PENDING')),
            'approved_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'APPROVED')),
            'rejected_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'REJECTED'))
        ];

        $this->respondSuccess([
            'message' => 'Dashboard loaded',
            'user' => [
                'id' => $user['user_id'],
                'name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role_name'],
                'department' => $user['department_name'] ?? 'N/A'
            ],
            'stats' => $stats,
            'recent_requests' => $myRequests
        ], 200);
    }

    public function getStats()
    {
        $this->requireAuth();

        $myRequests = $this->requestModel->getByRequestor($this->userId);

        $stats = [
            'total_requests' => count($myRequests),
            'pending_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'PENDING')),
            'in_progress_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'IN_PROGRESS')),
            'approved_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'APPROVED')),
            'rejected_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'REJECTED')),
            'completed_requests' => count(array_filter($myRequests, fn($r) => $r['overall_status'] === 'COMPLETED'))
        ];

        $this->respondSuccess(['stats' => $stats], 200);
    }

    public function show()
    {
        $this->requireAuth();
        include VIEW_PATH . '/dashboard/index.php';
    }
}
