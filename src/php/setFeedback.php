<?php
header('Content-Type: application/json');

include 'connection.php';
session_start();

try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    if (isset($data['action'])) {
        $action = $data['action'];

        if ($action === 'open') {
            // Logic to open feedback
            // Example: update database, etc.
            $response = [
                'status' => 'success',
                'message' => 'Feedback has been opened.'
            ];
        } elseif ($action === 'close') {
            // Logic to close feedback
            $response = [
                'status' => 'success',
                'message' => 'Feedback has been closed.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid action. Action must be "open" or "close".'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No action specified.'
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'An error occurred: ' . $e->getMessage()
    ];
    echo json_encode($response);
}
