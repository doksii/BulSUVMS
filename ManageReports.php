<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if ($_SESSION['super_admin'] !== 'yes') {
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
    <title>BulSUSDMS</title>
    <link rel="stylesheet" href="assets\css\MainStyle.css">
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
                <h2>Welcome to Manage Reports, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <input type="text" class="searchBar" id="searchBar" onkeyup="filterTable()" placeholder="Search for students..">
            <div class="scroll-container">
                <table id="reportTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"> Select All</th>
                            <th>Incident no.</th>
                            <th>Student Name</th>
                            <th>Violation</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody id="reportsBody">
                        <?php
                        require_once 'php/db.php';

                        $sql = "SELECT id, student_name, violation, created_at, created_by FROM reports ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td><input type='checkbox' class='reportSelect' value='" . $row["id"] . "'></td>
                                        <td>" . $row["id"]. "</td>
                                        <td>" . $row["student_name"]. "</td>
                                        <td>" . $row["violation"]. "</td>
                                        <td>" . $row["created_at"]. "</td>
                                        <td>" . $row["created_by"]. "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No reports found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <button class="UnivButton" id="exportBtn">Export Selected Reports as PDF</button>

            <div id="exportModal" class="modal">
                <div class="modal-content2">
                    <h3>Confirm Export</h3>
                    <p>Please enter your password to confirm the export:</p>
                    <button class="UnivButton" id="confirmExportBtn">Confirm Export</button>
                    <button class="UnivButton" id="cancelBtn">Cancel</button>
                </div>
            </div>

        <script>
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('status')) {
                    const status = urlParams.get('status');
                    if (status === 'success') {
                        alert('Account created successfully.');
                    } else if (status === 'error') {
                        alert('There was an error creating the account. Please try again.');
                    } 
                }
            };
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
            function toggleSelectAll(selectAllCheckbox) {
                const checkboxes = document.querySelectorAll('.reportSelect');
                const rows = document.querySelectorAll('#reportsBody tr');

                checkboxes.forEach(checkbox => {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = selectAllCheckbox.checked;
                    }
                });
            }

            document.getElementById('exportBtn').addEventListener('click', function() {

                document.getElementById('exportModal').style.display = 'block';
            });

            document.getElementById('cancelBtn').addEventListener('click', function() {

                document.getElementById('exportModal').style.display = 'none';
            });

            document.getElementById('confirmExportBtn').addEventListener('click', function() {

                let selectedReports = [];
                document.querySelectorAll('input[class="reportSelect"]:checked').forEach(checkbox => {
                    selectedReports.push(checkbox.value);
                });

                if (selectedReports.length === 0) {
                    alert('Please select at least one report to export.');
                    return;
                }

                const formData = new FormData();
                formData.append('report_ids', JSON.stringify(selectedReports));

                fetch('php/export_report_process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.blob())
                .then(blob => {

                    document.getElementById('exportModal').style.display = 'none';
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'BulSUSDMS-reports.pdf';
                    link.click();
                })
                .catch(error => {
                    console.error('Error exporting reports:', error);
                    alert('Failed to export reports.');
                });
                setTimeout(() => {
                    location.reload();
                }, 2500);
            });
        </script>
        <script src="js\script.js"></script>
</body>
</html>
