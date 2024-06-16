<?php
session_start();
require_once 'php/db.php'; // Ensure this path is correct
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: index.html");
    exit();
}

// Check if the user has the appropriate role (e.g., 'admin')
if ($_SESSION['owner'] !== 'yes') {
    // Redirect to a different page or show an error message
    header("Location: Settings.php?status=failed");
    echo "Access denied. You do not have the necessary permissions to view this page. Only owner can access this page";
    exit();
}

$current_username = $_SESSION['username']; // Assuming username is stored in the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BulSUVMS</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/css/MainStyle.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'success') {
                    alert('User role updated successfully.');
                } else if (status === 'error') {
                    alert('There was an error updating the user role. Please try again.');
                }
            }
        };
    </script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="logo.png" alt="Company Logo" class="logo">
        </div>
        <div class="company-name">Company Name</div>
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
                    <li><a href="AddStudents.php">Add Students</a></li>
                    <p>Option</p>
                    <li><a href="Settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <h1>Manage Accounts</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Display Name</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all users except the currently logged-in user
                    $sql = "SELECT username, display_name, role, super_admin FROM users WHERE username != ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("s", $current_username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['display_name']) . "</td>";
                                echo "<td>";
                                echo "<form method='POST' action='php/update_role.php'>";
                                echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
                                echo "<select name='super_admin'>";
                                echo "<option value='no'" . ($row['super_admin'] == 'no' ? " selected" : "") . ">Admin</option>";
                                echo "<option value='yes'" . ($row['super_admin'] == 'yes' ? " selected" : "") . ">Super Admin</option>";
                                echo "</select>";
                                echo "</td>";
                                echo "<td><button type='submit'>Update</button></td>";
                                echo "</form>";
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
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
