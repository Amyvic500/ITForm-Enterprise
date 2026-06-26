<?php
namespace App\Controllers;

class ApprovalController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pending()
    {
        include BASE_PATH . '/views/approvals/pending.php';
    }

    public function approve()
    {
        if ($this->requestMethod === 'POST') {
            $this->respondSuccess(['message' => 'Approval recorded']);
            return;
        }
        $this->respondWithError(405, 'Method not allowed');
    }
}
?>