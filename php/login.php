<?php
session_start();
require_once 'db.php'; // Adjust path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role']; // Add role to session
            $_SESSION['display_name'] = $row['display_name'];
            $_SESSION['super_admin'] = $row['super_admin'];
            $_SESSION['owner'] = $row['owner'];
            // Redirect to dashboard.php (outside the php folder)
            header("Location: ../dashboard.php");
            exit(); // Ensure that script execution stops after redirection
        } else {
            // Invalid password
            header("Location: ../index.html?error=invalid_credentials");
            exit();
        }
    } else {
        // Invalid username
        header("Location: ../index.html?error=invalid_credentials");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>