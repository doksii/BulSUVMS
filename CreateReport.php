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
                <a href="#myaccount">View Profile</a>
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
                    <li><a href="SearchStudents.php">Search Students</a></li>
                    <li><a href="AddStudents.php">Add Students</a></li>
                    <p>Option</p>
                    <li><a href="Settings.php">Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <!-- Content of the dashboard page goes here -->
            <h1>Welcome to the CreateReport!</h1>
            <p>This is a simple CreateReport page.</p>
            <form action="php/createreport_process.php" method="post">
                <label for="student_search_by">Search by:</label><br>
                <select id="student_search_by" name="student_search_by" required>
                    <option value="student_number">Student Number</option>
                    <option value="name">Name</option>
                </select><br><br>

                <label for="student_query">Student:</label><br>
                <input type="text" id="student_query" name="student_query" required><br><br>

                <label for="violation">Violation:</label><br>
                <select id="violation" name="violation" required>
                    <option value="Light Offenses: Littering or distribution of unauthorized printed material">Light Offenses: Littering or distribution of unauthorized printed material</option>
                    <option value="Light Offenses: Vandalism or unauthorized posting of printed materials">Light Offenses: Vandalism or unauthorized posting of printed materials</option>
                    <option value="Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities">Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities</option>
                    <option value="Light Offenses: Unauthorized solicitation of funds or selling of any ticket">Grave Offenses: Physical/verbal/sexual/mental/emotional abuse, threat, harassment, cyber bullying, hazing, coercion and/or other conduct that threatens or endangers the health or safety of any person</option>
                </select><br><br>
                <label for="no_of_offense">Number of Offenses:</label><br>
                <select id="no_of_offense" name="no_of_offense" required>
                    <option value="1st offense">1st Offense</option>
                    <option value="2nd offense">2nd Offense</option>
                    <option value="3rd offense">3rd Offense</option>
                    <option value="4th offense">4th Offense</option>
                    <option value="5th offense">5th Offense</option>
                </select><br><br>
                <label for="detailed_report">Detailed Report:</label><br>
                <textarea id="detailed_report" name="detailed_report" rows="4" required></textarea><br><br>

                <label for="date_of_violation">Date of Violation:</label><br>
                <input type="date" id="date_of_violation" name="date_of_violation" required><br><br>

                <label for="action_taken">Action Taken:</label><br>
                <input type="text" id="action_taken" name="action_taken" required><br><br>

                <!-- Hidden field to store the user ID -->
                <input type="hidden" name="created_by" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">

                <input type="submit" value="Create Report">
            </form>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
<!-- asdasd -->