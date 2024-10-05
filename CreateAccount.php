<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: index.html");
    exit();
}
// Check if the user has the appropriate role (e.g., 'admin')
if ($_SESSION['owner'] !== 'yes') {
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
    <link rel="icon" href="assets\img\BMCLogo.png" type="image/png">
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
            <img src="assets\img\BMCLogo.png" alt="Company Logo" class="logo">
        </div>
        <div class="company-name">
            <div class="company-name-container">
                <h1 class="company-name1">BULACAN STATE UNIVERSITY MENESES</h1>
                <h2 class="company-name2">VIOLATION MANAGEMENT SYSTEM</h2>
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
                    <li><a href="AddStudents.php">Add Students</a></li>
                    <p>Option</p>
                    <li><a href="Settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="MainContainer">
            <!-- Content of the dashboard page goes here -->
            <div class="WelcomeMessage">
                <h2>Welcome, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <div class="CreateAccountContent">
                <div class="FormContainer">
                    <form action="php/createaccount_process.php" method="POST">
                        <label for="display_name">Display Name:</label>
                        <input type="text" id="display_name" name="display_name" placeholder="Enter Name" required>

                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Enter Username" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter Password" required>

                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter Password Again" required>

                        <label for="role">Role:</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>--Select Role--</option>
                            <option value="admin">Operator</option>
                            <option value="super_admin">Admin</option>
                        </select>
                        <button type="submit">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
