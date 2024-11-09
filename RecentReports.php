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
    <link rel="stylesheet" href="assets\css\MainStyle.css">
    <script src="js/script.js"></script>
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchBar");
            filter = input.value.toUpperCase();
            table = document.getElementById("reportTable");
            tr = table.getElementsByTagName("tr");
            
            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("reportTable");
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;      
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
        function viewStudent(studentNumber) {
            fetch(`php/fetch_student_info.php?student_number=${studentNumber}`)
                .then(response => response.json())
                .then(data => {
                    const studentInfo = `
                        <p><strong>Student Name:</strong>${data.student.name}</p>
                        <p><strong>Student Number:</strong> ${data.student.student_number}</p>
                        <p><strong>Gender:</strong> ${data.student.gender}</p>
                        <p><strong>Department:</strong> ${data.student.department}</p>
                    `;
                    document.getElementById('studentInfo').innerHTML = studentInfo;

                    let reportsHTML = '<table><tr><th>Violation</th><th>No of Offenses</th><th>Detailed Report</th><th>Date of Violation</th><th>Action Taken</th></tr>';
                    data.reports.forEach(report => {
                        reportsHTML += `<tr><td>${report.violation}</td><td>${report.no_of_offense}</td><td>${report.detailed_report}</td><td>${report.date_of_violation}</td><td>${report.action_taken}</td></tr>`;
                    });
                    reportsHTML += '</table>';
                    document.getElementById('reportsTable').innerHTML = reportsHTML;

                    document.getElementById('studentModal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
        function closeModal() {
            document.getElementById('studentModal').style.display = 'none';
        }
        function filterReports() {
            const input = document.getElementById("searchReports");
            const filter = input.value.toLowerCase();
            const table = document.querySelector("#reportsTable table");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                const tdArray = tr[i].getElementsByTagName("td");
                let found = false;
                for (let j = 0; j < tdArray.length; j++) {
                    if (tdArray[j]) {
                        if (tdArray[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
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
                    <li><a href="RecentReports.php" style="background-color: #4e4d4d;">Recent Reports</a></li>
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
                <h2>Welcome to Recent Reports, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <input type="text" id="searchBar" class="searchBar" onkeyup="filterTable()" placeholder="Search for reports..">
            <div class="scroll-container">
                <table id="reportTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Incident no.</th>
                            <th onclick="sortTable(1)">Student Name</th>
                            <th onclick="sortTable(2)">Violation</th>
                            <th onclick="sortTable(3)">Date Created</th>
                            <th onclick="sortTable(4)">Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'php/db.php';

                        $sql = "SELECT id, student_number, student_name, violation, created_at, created_by FROM reports ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . $row["id"]. "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . htmlspecialchars($row["student_name"]). "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . htmlspecialchars($row["violation"]). "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . htmlspecialchars($row["created_at"]). "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . htmlspecialchars($row["created_by"]). "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No reports found</td></tr>";
                        }

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
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="studentInfo"></div>
            <h3>Assiociated violations:</h3>
            <input type="text" class="searchReports" id="searchReports" onkeyup="filterReports()" placeholder="Search for reports..">
            <div id="reportsTable" class="reports-table"></div><br><br>
            <button onclick="location.href='CreateReport.php'" class="modal-add-btn">Add report</button>
        </div>
    </div>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
</body>
</html>
