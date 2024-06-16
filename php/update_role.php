<?php
session_start();
require_once 'db.php'; // Ensure this path is correct


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_super_admin = $_POST['super_admin'];

    // Update user role in the database
    $sql = "UPDATE users SET super_admin = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $new_super_admin, $username);
        if ($stmt->execute()) {
            header("Location: ../ManageAccounts.php?status=success");
        } else {
            header("Location: ../ManageAccounts.php?status=error");
        }
        $stmt->close();
    } else {
        header("Location: ../ManageAccounts.php?status=error");
    }
    $conn->close();
} else {
    header("Location: ../ManageAccounts.php");
}
