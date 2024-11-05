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
                    alert('Student added successfully.');
                    window.location.href = "SearchStudents.php";
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
                <h2>Welcome to Add Student, <?php echo $_SESSION['display_name']; ?>!</h2>
             </div>
            <div class="AddStudentContent">
                <div class="FormContainer">
                    <h2>Student Information</h2>
                    <p>Please fill the information needed.</p>
                    <form action="php/addstudent_process.php" method="post">
                        <label for="name">Name: (Lastname, Firstname MI)</label>
                        <input type="text" id="name" name="name" placeholder="Enter student name" required>

                        <label for="student_number">Student Number:</label>
                        <input type="text" id="student_number" name="student_number" placeholder="Enter student number" required>

                        <label for="gender">Gender:</label><br>
                        <select id="gender" name="gender" required>
                        <option value="" disabled selected>--Select Gender--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>

                        <label for="department">Department:</label><br>
                        <select id="department" name="department" required>
                            <option value="" disabled selected>--Select Department--</option>
                            <option value="BIT">BIT Department</option>
                            <option value="BSBA">BSBA Department</option>
                            <option value="BSCpE">BSCpE Department</option>
                            <option value="BSED">BSED Department</option>
                            <option value="BSHM">BSHM Department</option>
                            <option value="BSIT">BSIT Department</option>
                        </select>

                        <button type="submit">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
