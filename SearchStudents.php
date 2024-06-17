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
    <link rel="stylesheet" href="assets\css\MainStyle.css">
    <link rel="stylesheet" href="assets\css\search-students.css">

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
            <h1>Search Students</h1>
            <input type="text" id="searchBar" onkeyup="filterTable()" placeholder="Search for students..">
            <div class="scroll-container">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Student Number</th>
                            <th onclick="sortTable(1)">Name</th>
                            <th onclick="sortTable(2)">Gender</th>
                            <th onclick="sortTable(3)">Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace with your database credentials
                        require_once 'php/db.php'; // Adjust path as per your file structure

                        // Fetch student records
                        $sql = "SELECT id, student_number, name, gender, department FROM students";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["student_number"]. "</td>
                                        <td>" . $row["name"]. "</td>
                                        <td>" . $row["gender"]. "</td>
                                        <td>" . $row["department"]. "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No students found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="studentInfoContainer">
                <!-- Student details and reports will be displayed here -->
            </div>
        </div>
    </div>

    <script src="js/search-students.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
