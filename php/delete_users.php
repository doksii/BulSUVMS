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
            // Password is correct, proceed with deleting users
            if (isset($_POST['delete_users']) && is_array($_POST['delete_users'])) {
                $delete_users = $_POST['delete_users'];

                $error = false;

                // Delete each selected user
                foreach ($delete_users as $username) {
                    $sql_delete = "DELETE FROM users WHERE username = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("s", $username);
                    if (!$stmt_delete->execute()) {
                        $error = true;
                        break;
                    }
                    $stmt_delete->close();
                }

                if ($error) {
                    header("Location: ../ManageAccounts.php?status=error");
                } else {
                    header("Location: ../ManageAccounts.php?status=delete_success");
                }
            } else {
                header("Location: ../ManageAccounts.php?status=error");
            }
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
