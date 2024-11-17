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
    <style>
        input, select {
            display: block;
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .l, .r {
            width: 50%;
        }
        .m {
            display: flex;
            justify-content: center;
        }
    </style>
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
                    document.getElementById('editStudentName').value = data.student.name;
                    document.getElementById('editStudentNumber').value = data.student.student_number;
                    document.getElementById('originalStudentNumber').value = data.student.student_number;  // Set original student number to hidden input
                    document.getElementById('editGender').value = data.student.gender;
                    document.getElementById('editDepartment').value = data.student.department;

                    let reportsHTML = '<table><tr><th>Violation</th><th>Detailed Report</th><th>Action Taken</th><th>No. of Offense</th><th>Created By</th></tr>';
                    data.reports.forEach(report => {
                        reportsHTML += `<tr><td>${report.violation}</td><td>${report.detailed_report}</td><td>${report.action_taken}</td><td>${report.no_of_offense}</td><td>${report.created_by}</td></tr>`;
                    });
                    reportsHTML += '</table>';
                    document.getElementById('reportsTable').innerHTML = reportsHTML;

                    document.getElementById('editStudentModal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('editStudentModal').style.display = 'none';
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
        function saveChanges() {
            const statusElements = document.querySelectorAll('.status-dropdown');
            const updates = [];

            // Gather all changed status values
            statusElements.forEach(element => {
                const reportId = element.getAttribute('data-report-id');
                const status = element.value;
                updates.push({ report_id: reportId, status: status });
            });

            // Send AJAX request to update statuses in the database
            fetch('php/update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ updates: updates })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Updated successfully!');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error executing changes.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
        function confirmEdit() {
            const confirmation = confirm("Are you sure you want to save the changes?");
            if (confirmation) {
                const updatedData = {
                    original_student_number: document.getElementById('originalStudentNumber').value,  // Get the value from the hidden input
                    student_number: document.getElementById('editStudentNumber').value,
                    name: document.getElementById('editStudentName').value,
                    gender: document.getElementById('editGender').value,
                    department: document.getElementById('editDepartment').value
                };

                fetch('php/update_student_info.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updatedData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Student information updated successfully.");
                        location.reload();
                    } else {
                        alert(data.message || "An error occurred while updating.");
                    }
                })
                .catch(error => console.error('Error:', error));
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
                    <li><a href="RecentReports.php">Recent Reports</a></li>
                    <li><a href="CreateReport.php">Create Report</a></li>
                    <p>Students</p>
                    <li><a href="SearchStudents.php">List of Students</a></li>
                    <li><a href="AddStudents.php">Add Student</a></li>
                    <p>Option</p>
                    <li><a href="Settings.php" style="background-color: #4e4d4d;">Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="MainContainer">
            <div class="WelcomeMessage">
                <h2>Welcome to Edit Student Information, <?php echo $_SESSION['display_name']; ?>!</h2>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'php/db.php';
                        $sql = "SELECT student_number, name, gender, department FROM students";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
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

                        $conn->close();
                        ?>
                    </tbody>
                </table>

                <div id="editStudentModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h3>Edit Student Information</h3>
                        
                        <!-- Hidden input for original student number -->
                        <input type="hidden" id="originalStudentNumber" name="originalStudentNumber">
                        <div class="m">
                            <div class="l">
                                <label for="editStudentName">Student Name:</label>
                                <input type="text" id="editStudentName" name="editStudentName" readonly>
                                
                                <label for="editStudentNumber">Student Number:</label>
                                <input type="text" id="editStudentNumber" name="editStudentNumber">
                            </div>
                            <div class="r">
                                <label for="editGender">Gender:</label>
                                <select id="editGender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Null">Not Specified</option>
                                </select>

                                <label for="editDepartment">Department:</label>
                                <select id="editDepartment" name="editDepartment" required>
                                    <option value="BIT">BIT Department</option>
                                    <option value="BSBA">BSBA Department</option>
                                    <option value="BSCpE">BSCpE Department</option>
                                    <option value="BSED">BSED Department</option>
                                    <option value="BSHM">BSHM Department</option>
                                    <option value="BSIT">BSIT Department</option>
                                    <option value="Not Specified">Not Specified</option>
                                </select>
                            </div>
                        </div>
                        

                        <h3>Associated Violations:</h3>
                        <input type="text" class="searchReports" id="searchReports" onkeyup="filterReports()" placeholder="Search for reports..">
                        <div id="reportsTable" class="reports-table"></div><br><br>

                        <button onclick="confirmEdit()" class="modal-add-btn">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
</body>
</html>
