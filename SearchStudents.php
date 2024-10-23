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
    <script src="js/script.js"></script>
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchBar");
            filter = input.value.toUpperCase();
            table = document.getElementById("studentTable");
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
            table = document.getElementById("studentTable");
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i+1].getElementsByTagName("TD")[n];
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
            // Fetch student and report information using AJAX
            fetch(`php/fetch_student_info.php?student_number=${studentNumber}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal with student information
                    const studentInfo = `
                        <p><strong>Student Name:</strong>${data.student.name}</p>
                        <p><strong>Student Number:</strong> ${data.student.student_number}</p>
                        <p><strong>Gender:</strong> ${data.student.gender}</p>
                        <p><strong>Department:</strong> ${data.student.department}</p>
                    `;
                    document.getElementById('studentInfo').innerHTML = studentInfo;

                    // Populate the modal with reports
                    let reportsHTML = '<table><tr><th>Violation</th><th>No of Offenses</th><th>Detailed Report</th><th>Date of Violation</th><th>Action Taken</th></tr>';
                    data.reports.forEach(report => {
                        reportsHTML += `<tr><td>${report.violation}</td><td>${report.no_of_offense}</td><td>${report.detailed_report}</td><td>${report.date_of_violation}</td><td>${report.action_taken}</td></tr>`;
                    });
                    reportsHTML += '</table>';
                    document.getElementById('reportsTable').innerHTML = reportsHTML;

                    // Show the modal
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
            <div class="WelcomeMessage">
                <h2>Welcome to List of Students, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <input type="text" id="searchBar" class="searchBar" onkeyup="filterTable()" placeholder="Search for students..">
            <div class="scroll-container">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Student Number</th>
                            <th onclick="sortTable(1)">Name</th>
                            <th onclick="sortTable(2)">Gender</th>
                            <th onclick="sortTable(3)">Department</th>
                            <!-- <th>Action</th> -->
                            <!-- <td><button onclick=\"viewStudent('" . $row['student_number'] . "')\">View</button></td> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'php/db.php'; // Adjust path as per your file structure

                        // Fetch student records
                        $sql = "SELECT student_number, name, gender, department FROM students";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . $row["student_number"] . "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . $row["name"] . "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . $row["gender"] . "</td>
                                        <td onclick=\"viewStudent('" . $row['student_number'] . "')\">" . $row["department"] . "</td>
                                        
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No students found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>

                <!-- Pop-up Modal -->
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
            </div>
        </div>
    </div>
</body>
</html>
