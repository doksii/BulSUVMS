<?php
require_once 'db.php';

// Get JSON data from AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

// Check if data is valid
if (isset($data['updates']) && is_array($data['updates'])) {
    $success = true;
    foreach ($data['updates'] as $update) {
        $report_id = intval($update['report_id']);
        $status = $conn->real_escape_string($update['status']);

        // Update each report's status
        $sql = "UPDATE reports SET status = '$status' WHERE id = $report_id";
        if (!$conn->query($sql)) {
            $success = false;
            break;
        }
    }
    $response['success'] = $success;
}

// Send response back to AJAX
echo json_encode($response);

$conn->close();
?>
