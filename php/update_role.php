<?php
session_start();
require_once 'db.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_username = $_SESSION['username'];
    $current_password = $_POST['current_password'];

    // Verify the current password
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $current_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($current_password, $row['password'])) {
            // Password is correct, proceed with updating roles
            $super_admin_updates = $_POST['super_admin'];

            foreach ($super_admin_updates as $username => $super_admin) {
                $sql_update = "UPDATE users SET super_admin = ? WHERE username = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ss", $super_admin, $username);
                $stmt_update->execute();
                $stmt_update->close();
            }

            header("Location: ../ManageAccounts.php?status=success");
        } else {
            header("Location: ../ManageAccounts.php?status=password_error");
        }
    } else {
        header("Location: ../ManageAccounts.php?status=error");
    }

    $stmt->close();
    $conn->close();
}
?>
