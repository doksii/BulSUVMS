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
    <title>BulSUVMS</title>
    <link rel="stylesheet" href="assets\css\MainStyle.css">
    <link rel="stylesheet" href="assets\css\search-students.css">
    <style>
        /* Modal Styles */
        .scroll-container {
            max-height: 60vh; /* Set max height for the container */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-confirm-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            float: right;
        }

        .modal-confirm-btn:hover {
            background-color: #45a049;
        }

        .modal-cancel-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            position: relative;
            left: 30%;
        }

        .modal-cancel-btn:hover {
            background-color: #da190b;
        }
    </style>
    <script>
        function selectAllStudents() {
            var selectAllCheckbox = document.getElementById('selectAllCheckbox');
            var checkboxes = document.getElementsByName('select_student');

            var filteredCheckboxes = Array.from(checkboxes).filter(function(checkbox) {
                return checkbox.closest('tr').style.display !== 'none'; // Filter only visible rows
            });

            for (var i = 0; i < filteredCheckboxes.length; i++) {
                filteredCheckboxes[i].checked = selectAllCheckbox.checked;
            }
        }
        function confirmDelete() {
            const checkboxes = document.querySelectorAll('input[name="select_student"]:checked');
            if (checkboxes.length === 0) {
                alert("Please select at least one student to delete.");
                return;
            }
            document.getElementById('confirmationModal').style.display = "flex";
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = "none";
        }

        function deleteStudents() {
            const password1 = document.getElementById('password1').value;
            const password2 = document.getElementById('password2').value;

            if (password1 !== password2) {
                alert("Passwords do not match. Please try again.");
                return;
            }

            const selectedStudents = [];
            document.querySelectorAll('input[name="select_student"]:checked').forEach(checkbox => {
                selectedStudents.push(checkbox.value);
            });

            const formData = new FormData();
            formData.append('password', password1);
            formData.append('students', JSON.stringify(selectedStudents));

            fetch('php/delete_students.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Selected students deleted successfully.");
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
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
            <h2>Manage Students</h2>
            <input type="text" id="searchBar" onkeyup="filterTable()" placeholder="Search for students..">
            <div class="scroll-container">
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Student Number</th>
                            <th onclick="sortTable(1)">Name</th>
                            <th onclick="sortTable(2)">Gender</th>
                            <th onclick="sortTable(3)">Department</th>
                            <th>Select All<input type="checkbox" id="selectAllCheckbox" onchange="selectAllStudents()"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch student records
                        require_once 'php/db.php';

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
                                        <td><input type='checkbox' name='select_student' value='" . $row["id"] . "'></td>
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
                

                <!-- Modal -->
                <div id="confirmationModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>Confirm Deletion</h2>
                        <p>This action cannot be undone. Please enter your password twice to confirm:</p>
                        <form id="confirmForm">
                            <label for="password1">Password:</label>
                            <input type="password" id="password1" name="password1" required><br><br>
                            <label for="password2">Confirm Password:</label>
                            <input type="password" id="password2" name="password2" required><br><br>
                            <button type="button" class="modal-cancel-btn" onclick="closeModal()">Cancel</button>
                            <button type="button" class="modal-confirm-btn" onclick="deleteStudents()">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
            <button onclick="confirmDelete()">Delete Selected Students</button>
        </div>
    </div>

    <script src="js/search-students.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
