<?php
session_start();
require_once 'php/db.php';
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if ($_SESSION['owner'] !== 'yes') {
    header("Location: Settings.php?status=failed");
    echo "Access denied. You do not have the necessary permissions to view this page. Only owner can access this page";
    exit();
}
$current_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets\img\BMCLogo.png" type="image/png">
    <title>BulSU-MC-SDMS</title>
    <link rel="stylesheet" href="assets/css/MainStyle.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'success') {
                    alert('User role updated successfully.');
                } else if (status === 'delete_success') {
                    alert('User account deleted successfully.');
                } else if (status === 'error') {
                    alert('There was an error updating the user role. Please try again.');
                } else if (status === 'password_error') {
                    alert('Incorrect password. Please try again.');
                }
            }
        };
        function confirmDelete() {
            if (confirm('Are you sure you want to delete the selected users?')) {
                document.getElementById('updateForm').action = 'php/delete_users.php';
                document.getElementById('updateForm').submit();
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="assets\img\BMCLogo.png" alt="Company Logo" class="logo">
        </div>
        <div class="company-name">
            <div class="company-name-container">
                <h1 class="company-name1">BULACAN STATE UNIVERSITY MENESES</h1>
                <h2 class="company-name2">STUDENT DISCIPLINE MANAGEMENT SYSTEM</h2>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">My Account</button>
            <div class="dropdown-content">
                <a href="AccountSettings.php"><?php echo $_SESSION['username']; ?></a>
                <a href="php/logout.php">Logout</a>
            </div>
        </div>
    </header>
    <div class="dashboard-container">
        <div class="hamburger-menu" onclick="toggleSidebar()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="menu" id="menu">
                <ul>
                    <p>Home</p>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <p>Reports</p>
                    <li><a href="RecentReports.php">Recent Reports</a></li>
                    <li><a href="CreateReport.php">Create Report</a></li>
                    <p>Students</p>
                    <li><a href="SearchStudents.php">List of Students</a></li>
                    <li><a href="AddStudents.php">Add Student</a></li>
                    <p>Option</p>
                    <li><a href="Settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="MainContainer">
            <div class="WelcomeMessage">
                <h2>Welcome to Manage Accounts, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <form method="POST" action="php/update_role.php" id="updateForm">
                <table border="1">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Username</th>
                            <th>Display Name</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT username, display_name, role, super_admin FROM users WHERE username != ?";
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param("s", $current_username);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='delete_users[]' value='" . htmlspecialchars($row['username']) . "'></td>";
                                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['display_name']) . "</td>";
                                    echo "<td>";
                                    echo "<select name='super_admin[" . htmlspecialchars($row['username']) . "]'>";
                                    echo "<option value='no'" . ($row['super_admin'] == 'no' ? " selected" : "") . ">Operator</option>";
                                    echo "<option value='yes'" . ($row['super_admin'] == 'yes' ? " selected" : "") . ">Admin</option>";
                                    echo "</select>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No users found</td></tr>";
                            }

                            $stmt->close();
                        } else {
                            echo "<tr><td colspan='4'>Query preparation failed: " . $conn->error . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <input type="password" class="MAinput" name="current_password" placeholder="Enter your password to confirm" required>
                <button type="submit" class="UnivButton">Save Changes</button>
            </form>
            <button onclick="confirmDelete()" class="UnivButton">Delete Selected Users</button>
        </div>
    </div>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
