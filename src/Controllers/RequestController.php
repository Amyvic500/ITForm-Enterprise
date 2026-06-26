<?php
namespace App\Controllers;

class RequestController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list()
    {
        include BASE_PATH . '/views/requests/list.php';
    }

    public function create()
    {
        if ($this->requestMethod === 'POST') {
            $this->respondSuccess(['message' => 'Request created']);
            return;
        }
        $this->respondWithError(405, 'Method not allowed');
    }

    public function createForm()
    {
        include BASE_PATH . '/views/requests/create.php';
    }
}
?>