<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: index.html");
    exit();
}

// Check if the user has the appropriate role (e.g., 'admin')
if ($_SESSION['super_admin'] !== 'yes') {
    // Redirect to a different page or show an error message
    header("Location: Settings.php?status=failed");
    echo "Access denied. You do not have the necessary permissions to view this page.";
    exit();
}
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
                    alert('Account created successfully.');
                } else if (status === 'error') {
                    alert('There was an error creating the account. Please try again.');
                } else if (status === 'duplicate') {
                    alert('Username already exists. Please choose a different username.');
                } else if (status === 'password_mismatch') {
                    alert('There was an error creating the account. The password does not match. Please try again.');
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
            <!-- Content of the dashboard page goes here -->
            <h1>Welcome to the create acc!</h1>
            <p>This is a simple Settings page.</p>
            <form action="php/createaccount_process.php" method="POST">
                <label for="display_name">Display Name:</label>
                <input type="text" id="display_name" name="display_name" required><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required><br>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select><br>

                

                <input type="submit" value="Create Account">
            </form>

        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
