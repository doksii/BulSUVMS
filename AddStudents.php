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
    <style>
        #scanner-container {
            position: fixed;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5000;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 30px;
            color: white;
            cursor: pointer;
            z-index: 5100;
        }
        .close-btn:hover {
            color: black;
        }
        #video-container {
            margin-bottom: 20px;
        }

        .scanner {
            width: 100%;
            max-width: 500px;
            height: auto;
            border: 2px solid white;
        }

        #cam-qr-result {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
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
                    <li><a href="AddStudents.php" style="background-color: #4e4d4d;">Add Student</a></li>
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
                <div class="FormContainer" style="margin-top: 30px;">
                    <h2>Student Information</h2>
                    <p>Please fill the information needed.</p>

                    <button type="button" id="scanButton">Scan QR Code</button>

                    <form action="php/addstudent_process.php" method="post">
                        <label for="name">Name: (Lastname, Firstname MI)</label>
                        <input type="text" id="name" name="name" placeholder="Enter student name" required>

                        <label for="student_number">Student Number:</label>
                        <input type="text" id="student_number" name="student_number" placeholder="Enter student number/Copy name if N/A" required>
                        <div style="display: flex; Flex-direction: row; justify-content: space-between;">
                            <div style="width: 48%">
                                <label for="gender">Gender:</label><br>
                                <select id="gender" name="gender" required>
                                    <option value="Null">Not Specified</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div style="width: 48%">
                                <label for="department">Department:</label><br>
                                <select id="department" name="department" required>
                                    <option value="Not Specified">Not Specified</option>
                                    <option value="BIT">BIT Department</option>
                                    <option value="BSBA">BSBA Department</option>
                                    <option value="BSCpE">BSCpE Department</option>
                                    <option value="BSED">BSED Department</option>
                                    <option value="BSHM">BSHM Department</option>
                                    <option value="BSIT">BSIT Department</option>
                                </select>
                            </div>
                        </div>
                        

                        <button type="submit">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="scannerBox">
        <div id="scanner-container" style="display: none;">
            <button id="closeScanner" class="close-btn">×</button>
            <br><br> 
            <div id="video-container">
                <video id="qr-video" class="scanner"></video>
            </div>
            <b>Detected QR code: </b>
            <span id="cam-qr-result">None</span>
        </div>
    </div>
    <script type="module">
        import QrScanner from "./js/qr-scanner.min.js";
        const video = document.getElementById('qr-video');
        const camQrResult = document.getElementById('cam-qr-result');
        function setResult(label, result) {
            label.textContent = result.data;
            label.style.color = 'teal';

            clearTimeout(label.highlightTimeout);
            label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
            parseQrData(result.data);

            stopScanner();
        }
        const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
            onDecodeError: error => {
                camQrResult.textContent = error;
                camQrResult.style.color = 'inherit';
            },
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });
        document.getElementById('closeScanner').addEventListener('click', function() {
            stopScanner();
        });
        function resetScanner() {
            if (window.scanner) {
                window.scanner.stop();
            }

            document.getElementById('name').value = '';
            document.getElementById('department').value = 'Not Specified';
            document.getElementById('cam-qr-result').textContent = 'None';

            document.getElementById('scanner-container').style.display = 'none';
        }
        function startQrScanner() {
            resetScanner();
            document.getElementById('scanner-container').style.display = 'block';
            scanner.start();
            window.scanner = scanner;
        }
        function stopScanner() {
            if (window.scanner) {
                window.scanner.stop();
                document.getElementById('scanner-container').style.display = 'none';
            }
        }    
        function parseQrData(data) {
            console.log("Raw QR data:", data);

            const studentNoMatch = data.match(/Student No\.\:\s*(\d+)/);
            const nameMatch = data.match(/Full Name\:\s?([^\n]+)/);
            const programMatch = data.match(/Program\:\s*(.*)/);

            if (studentNoMatch) {
                document.getElementById('student_number').value = studentNoMatch[1];
            }
            if (nameMatch) {
                document.getElementById('name').value = nameMatch[1].trim();
            }
            
            if (programMatch) {
                const program = programMatch[1].trim();
                const department = getDepartmentFromProgram(program);
                document.getElementById('department').value = department;
            }
        }
        function getDepartmentFromProgram(program) {
            if (program.includes("Industrial Technology")) return "BIT";
            if (program.includes("Business Administration")) return "BSBA";
            if (program.includes("Computer Engineering")) return "BSCpE";
            if (program.includes("Education")) return "BSED";
            if (program.includes("Hospitality Management")) return "BSHM";
            if (program.includes("Information Technology")) return "BSIT";
            return "Not Specified";
        }
        window.onload = function() {
            const scanButton = document.getElementById('scanButton');
            if (scanButton) {
                scanButton.addEventListener('click', startQrScanner);
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'success') {
                    alert('Student added successfully.');
                    window.location.href = "SearchStudents.php";
                } else if (status === 'error') {
                    alert('There was an error adding the student. Student number already exists. Please try again.');
                }
            }
        }
    </script>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
