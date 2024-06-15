<?php
session_start();
require_once 'db.php'; // Adjust path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $display_name = $_POST['display_name'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: ../CreateAccount.php?status=password_mismatch");
        exit();
    }

    // Determine super_admin value based on role
    if ($role === 'super_admin') {
        $super_admin = 'yes';
        $role = 'admin';
    } else {
        $super_admin = 'no';
    }

    // Check if username already exists
    $sql_check = "SELECT id FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Duplicate username
        header("Location: ../CreateAccount.php?status=duplicate");
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind SQL statement to insert into `users` table
        $sql_insert = "INSERT INTO users (username, password, role, display_name, super_admin) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $username, $hashed_password, $role, $display_name, $super_admin);

        // Execute insertion
        if ($stmt_insert->execute()) {
            // Success
            header("Location: ../CreateAccount.php?status=success");
        } else {
            // Error
            header("Location: ../CreateAccount.php?status=error");
        }

        // Close statement
        $stmt_insert->close();
    }

    // Close connection
    $stmt_check->close();
    $conn->close();
}
?>
