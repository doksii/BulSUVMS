<?php
require_once 'db.php'; // Adjust path as per your file structure

if (isset($_GET['id'])) {
    $report_id = intval($_GET['id']);
    $sql = "SELECT * FROM reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
        echo json_encode($report);
    } else {
        echo json_encode(['error' => 'Report not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
