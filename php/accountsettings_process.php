<?php
session_start();
require_once 'db.php'; // Adjust path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_username = $_SESSION['username'];
    $new_username = $_POST['new_username'];
    $new_display_name = $_POST['new_display_name'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    $current_password = $_POST['current_password'];

    // Fetch current user data
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $current_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify current password
        if (password_verify($current_password, $hashed_password)) {
            // Check if new passwords match
            if ($new_password !== $confirm_new_password) {
                header("Location: ../AccountSettings.php?status=password_mismatch");
                exit();
            }

            // Check if the new username already exists
            if ($new_username !== $current_username) {
                $sql_check_username = "SELECT * FROM users WHERE username = ?";
                $stmt_check_username = $conn->prepare($sql_check_username);
                $stmt_check_username->bind_param("s", $new_username);
                $stmt_check_username->execute();
                $result_check_username = $stmt_check_username->get_result();

                if ($result_check_username->num_rows > 0) {
                    // Username already exists
                    header("Location: ../AccountSettings.php?status=username_exists");
                    exit();
                }

                $stmt_check_username->close();
            }

            // Update user data
            $update_sql = "UPDATE users SET username = ?, display_name = ?";

            // If new password is provided, update it
            if (!empty($new_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql .= ", password = ?";
            }

            $update_sql .= " WHERE username = ?";
            $stmt_update = $conn->prepare($update_sql);

            // Bind parameters based on whether a new password is provided
            if (!empty($new_password)) {
                $stmt_update->bind_param("ssss", $new_username, $new_display_name, $new_hashed_password, $current_username);
            } else {
                $stmt_update->bind_param("sss", $new_username, $new_display_name, $current_username);
            }

            // Execute the update
            if ($stmt_update->execute()) {
                // Update session variables
                $_SESSION['username'] = $new_username;
                $_SESSION['display_name'] = $new_display_name;

                header("Location: ../AccountSettings.php?status=success");
            } else {
                header("Location: ../AccountSettings.php?status=error");
            }

            $stmt_update->close();
        } else {
            // Incorrect current password
            header("Location: ../AccountSettings.php?status=incorrect_password");
        }
    } else {
        // User not found (should not happen if session is managed correctly)
        header("Location: ../AccountSettings.php?status=error");
    }

    $stmt->close();
    $conn->close();
}
?>
