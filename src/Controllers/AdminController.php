<?php
namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $this->requireAuth();
        $this->requirePermission('SYSTEM_CONFIG');

        include VIEW_PATH . '/admin/dashboard.php';
    }

    public function users()
    {
        $this->requireAuth();
        $this->requirePermission('MANAGE_USERS');

        $page = $this->getParam('page', 1);
        $search = $this->sanitize($this->getParam('search', ''));

        if (!empty($search)) {
            $users = $this->userModel->searchUsers($search);
        } else {
            $users = $this->userModel->getAllUsers($page);
        }

        $this->respondSuccess([
            'users' => $users,
            'count' => count($users)
        ], 200);
    }

    public function getUser()
    {
        $this->requireAuth();
        $this->requirePermission('MANAGE_USERS');

        $userId = $this->getParam('id');
        $this->validateRequired(['id']);

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $this->respondWithError(404, 'User not found');
        }

        $this->respondSuccess(['user' => $user], 200);
    }

    public function updateUser()
    {
        $this->requireAuth();
        $this->requirePermission('MANAGE_USERS');

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $userId = $this->getParam('user_id');
        $fullName = $this->sanitize($this->getParam('full_name'));
        $phone = $this->sanitize($this->getParam('phone'));
        $jobTitle = $this->sanitize($this->getParam('job_title'));
        $roleId = $this->getParam('role_id');

        $this->validateRequired(['user_id', 'full_name']);

        $user = $this->userModel->findById($userId);
        if (!$user) {
            $this->respondWithError(404, 'User not found');
        }

        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone,
            'job_title' => $jobTitle,
            'role_id' => $roleId
        ];

        $result = $this->userModel->update($userId, $updateData);

        if (!$result) {
            $this->respondWithError(500, 'Failed to update user');
        }

        $this->logAudit('USER_UPDATED', 'tbl_users', $userId, $user, $updateData);

        $this->respondSuccess(['message' => 'User updated successfully'], 200);
    }

    public function deactivateUser()
    {
        $this->requireAuth();
        $this->requirePermission('MANAGE_USERS');

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $userId = $this->getParam('user_id');
        $this->validateRequired(['user_id']);

        if ($userId == $this->userId) {
            $this->respondWithError(400, 'Cannot deactivate your own account');
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            $this->respondWithError(404, 'User not found');
        }

        $result = $this->userModel->softDelete($userId);

        if (!$result) {
            $this->respondWithError(500, 'Failed to deactivate user');
        }

        $this->logAudit('USER_DEACTIVATED', 'tbl_users', $userId);

        $this->respondSuccess(['message' => 'User deactivated'], 200);
    }

    public function settings()
    {
        $this->requireAuth();
        $this->requirePermission('SYSTEM_CONFIG');

        include VIEW_PATH . '/admin/settings.php';
    }

    public function updateSettings()
    {
        $this->requireAuth();
        $this->requirePermission('SYSTEM_CONFIG');

        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
        }

        $appName = $this->sanitize($this->getParam('app_name'));
        $approvalTimeout = $this->getParam('approval_timeout_days');
        $sessionTimeout = $this->getParam('session_timeout_minutes');
        $enableEmailNotifications = $this->getParam('enable_email_notifications');

        $this->validateRequired(['app_name', 'approval_timeout_days', 'session_timeout_minutes']);

        $settings = [
            'APP_NAME' => $appName,
            'APPROVAL_TIMEOUT_DAYS' => $approvalTimeout,
            'SESSION_TIMEOUT_MINUTES' => $sessionTimeout,
            'ENABLE_EMAIL_NOTIFICATIONS' => $enableEmailNotifications
        ];

        // TODO: Save to database (tbl_system_settings)

        $this->logAudit('SETTINGS_UPDATED', 'tbl_system_settings', null, null, $settings);

        $this->respondSuccess(['message' => 'Settings updated'], 200);
    }

    public function showUsersList()
    {
        $this->requireAuth();
        $this->requirePermission('MANAGE_USERS');

        include VIEW_PATH . '/admin/users.php';
    }

    public function showSettingsPage()
    {
        $this->requireAuth();
        $this->requirePermission('SYSTEM_CONFIG');

        include VIEW_PATH . '/admin/settings.php';
    }
}
