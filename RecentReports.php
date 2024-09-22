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
    <link rel="icon" href="assets\img\BMCLogo.png" type="image/png">
    <title>BulSUVMS</title>
    <link rel="stylesheet" href="assets\css\MainStyle.css">
    <link rel="stylesheet" href="assets\css\search-reports.css">
    <style>
        .content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden; /* Prevent content from overflowing */
        }
        .scroll-container {
            max-height: 60vh; /* Set max height for the container */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
        #searchBar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        /* table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        } */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 700px;
            max-height: 400px;
            overflow-y: auto;
        }
        .popup button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            position: relative;
            left: 90%;
            /* transform: translateX(-50%); */
        }
    </style>
    <script>
        function viewReport(reportId) {
            // Make an AJAX request to fetch the report details
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'php/get_report_details.php?id=' + reportId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var report = JSON.parse(xhr.responseText);
                    // Fill the pop-up with report details
                    document.getElementById('popupStudentName').textContent = report.student_name;
                    document.getElementById('popupViolation').textContent = report.violation;
                    document.getElementById('popupOffenses').textContent = report.no_of_offense;
                    document.getElementById('popupDetailedReport').textContent = report.detailed_report;
                    document.getElementById('popupDate').textContent = report.date_of_violation;
                    document.getElementById('popupActionTaken').textContent = report.action_taken;
                    document.getElementById('popupCreatedBy').textContent = report.created_by;
                    // Show the pop-up
                    document.getElementById('reportPopup').style.display = 'block';
                }
            };
            xhr.send();
        }

        function closePopup() {
            document.getElementById('reportPopup').style.display = 'none';
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
        <div class="content">
            <h1>Recent Reports</h1>
            <input type="text" id="searchBar" onkeyup="filterTable()" placeholder="Search for reports..">
            <div class="scroll-container">
                <table id="reportTable">
                    <thead>
                        <tr>
                            <th>Incident no.</th>
                            <th onclick="sortTable(1)">Student Name</th>
                            <th onclick="sortTable(2)">violation</th>
                            <th onclick="sortTable(3)">Date Created</th>
                            <th onclick="sortTable(4)">Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace with your database credentials
                        require_once 'php/db.php'; // Adjust path as per your file structure

                        // Fetch report records
                        $sql = "SELECT id, student_name, violation, created_at, created_by FROM reports ORDER BY created_at DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id"]. "</td>
                                        <td>" . htmlspecialchars($row["student_name"]). "</td>
                                        <td>" . htmlspecialchars($row["violation"]). "</td>
                                        <td>" . htmlspecialchars($row["created_at"]). "</td>
                                        <td>" . htmlspecialchars($row["created_by"]). "</td>
                                        <td><button onclick='viewReport(" . $row["id"]. ")'>View</button></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No reports found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="reportPopup" class="popup">
        <h2>Report Details</h2>
        <p><strong>Student Name:</strong> <span id="popupStudentName"></span></p>
        <p><strong>Violation:</strong> <span id="popupViolation"></span></p>
        <p><strong>Number of Offenses:</strong> <span id="popupOffenses"></span></p>
        <p><strong>Date of Violation:</strong> <span id="popupDate"></span></p>
        <p><strong>Action Taken:</strong> <span id="popupActionTaken"></span></p>
        <p><strong>Detailed Report:</strong> <span id="popupDetailedReport"></span></p>
        <p><strong>Created By:</strong> <span id="popupCreatedBy"></span></p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script src="js/script.js"></script>
    <script src="js/search-reports.js"></script>
</body>
</html>
