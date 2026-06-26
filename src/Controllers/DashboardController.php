<?php
namespace App\Controllers;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        include BASE_PATH . '/views/dashboard/index.php';
    }
}
?>