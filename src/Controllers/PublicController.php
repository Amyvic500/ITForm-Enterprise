<?php
namespace App\Controllers;

class PublicController extends BaseController
{
    public function requestForm()
    {
        include BASE_PATH . '/views/request-form.php';
    }

    public function createRequest()
    {
        if ($this->requestMethod !== 'POST') {
            $this->respondWithError(405, 'Method not allowed');
            return;
        }

        // Get JSON data
        $input = json_decode(file_get_contents('php://input'), true);
        
        // TODO: Insert into database
        // For now, just return success
        $this->respondSuccess([
            'message' => 'Request submitted successfully',
            'request_id' => 'REQ-' . time()
        ]);
    }
}
?>
