<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied. You do not have the necessary permissions to view this page.";
    exit();
}
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
                    alert('Account updated successfully.');
                } else if (status === 'error') {
                    alert('There was an error updating your account. Please try again.');
                } else if (status === 'password_mismatch') {
                    alert('New passwords do not match. Please try again.');
                } else if (status === 'incorrect_password') {
                    alert('Current password is incorrect. Please try again.');
                } else if (status === 'username_exists') {
                    alert('The new username already exists. Please choose a different username.');
                }
            }
        };
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
                <h2>Welcome to Account Settings, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <div class="AccountSettingContent">
                <div class="FormContainer">
                    <form id="accountSettingsForm" action="php/accountsettings_process.php" method="POST">

                        <label for="new_display_name">Display Name:</label>
                        <input type="text" id="new_display_name" readonly="readonly" name="new_display_name" value="<?php echo $_SESSION['display_name']; ?>">

                        <label for="new_username">Username:</label>
                        <input type="text" id="new_username" name="new_username" value="<?php echo $_SESSION['username']; ?>">

                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter New Password">

                        <label for="confirm_new_password">Confirm New Password:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Enter New Password Again">

                        <label for="current_password">Password (for verification):</label>
                        <input type="password" id="current_password" name="current_password" placeholder="Enter Current Password" required>

                        <button type="submit">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
