<?php
// Start the session and check for logged-in admin
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

// Include the database connection
require_once 'db.php';

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the required fields are present
if (!isset($data['original_student_number'], $data['student_number'], $data['name'], $data['gender'], $data['department'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit();
}

// Get the student information
$original_student_number = $data['original_student_number'];
$student_number = $data['student_number'];
$name = $data['name'];
$gender = $data['gender'];
$department = $data['department'];

// Start a transaction to ensure all updates happen atomically
$conn->begin_transaction();

try {
    // Update the student information in the students table
    $sql = "UPDATE students SET student_number = ?, name = ?, gender = ?, department = ? WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $student_number, $name, $gender, $department, $original_student_number);
    $stmt->execute();

    // Update the student_number in all associated reports
    $sql_reports = "UPDATE reports SET student_number = ? WHERE student_number = ?";
    $stmt_reports = $conn->prepare($sql_reports);
    $stmt_reports->bind_param("ss", $student_number, $original_student_number);
    $stmt_reports->execute();

    // Commit the transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Student information and associated reports updated successfully.']);
} catch (Exception $e) {
    // Rollback the transaction in case of any error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error updating student information and reports: ' . $e->getMessage()]);
}
