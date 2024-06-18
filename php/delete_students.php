<?php
session_start();
require_once 'db.php'; // Adjust path as needed

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred.',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $students = json_decode($_POST['students'], true);

    if (empty($password) || empty($students)) {
        $response['message'] = 'Password and student selection are required.';
        echo json_encode($response);
        exit();
    }

    // Verify user password
    $username = $_SESSION['username'];
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password verified, proceed with deletion
            $placeholders = implode(',', array_fill(0, count($students), '?'));
            $types = str_repeat('i', count($students));
            $sql_delete = "DELETE FROM students WHERE id IN ($placeholders)";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param($types, ...$students);

            if ($stmt_delete->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Selected students deleted successfully.';
            } else {
                $response['message'] = 'Error deleting students.';
            }

            $stmt_delete->close();
        } else {
            $response['message'] = 'Incorrect password.';
        }
    } else {
        $response['message'] = 'User not found.';
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
