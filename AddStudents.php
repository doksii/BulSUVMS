<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: index.html");
    exit();
}

// Check if the user has the appropriate role (e.g., 'admin')
if ($_SESSION['role'] !== 'admin') {
    // Redirect to a different page or show an error message
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
                    alert('Student added successfully.');
                } else if (status === 'error') {
                    alert('There was an error adding the student. Student number already exist. Please try again.');
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
            <h1>Welcome to the AddStudents!</h1>
            <p>This is a simple AddStudents page.</p>
            <form action="php/addstudent_process.php" method="post">
                <label for="name">Name: (Lastname, Firstname MI)</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="student_number">Student Number:</label><br>
                <input type="text" id="student_number" name="student_number" required><br><br>

                <label for="gender">Gender:</label><br>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select><br><br>

                <label for="department">Department:</label><br>
                <select id="department" name="department" required>
                    <option value="BIT">BIT Department</option>
                    <option value="BSIT">BSIT Department</option>
                    <option value="CPE">CPE Department</option>
                </select><br><br>

                <input type="submit" value="Add Student">
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
