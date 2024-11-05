<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied. You do not have the necessary permissions to view this page.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bulsuvms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_violations') {
    $sql = "SELECT COUNT(violation) AS total_unique_offenses FROM reports";
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $totalViolations = $row['total_unique_offenses'];
        echo json_encode(['totalViolations' => $totalViolations]);
    } else {
        echo json_encode(['error' => 'SQL error: ' . $conn->error]);
        exit();
    }

    $conn->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_num_students_violations') {
    $sql = "SELECT COUNT(DISTINCT student_name) AS total_students_with_violations FROM reports";
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $totalStudentsViolations = $row['total_students_with_violations'];
        echo json_encode(['totalStudentsViolations' => $totalStudentsViolations]);
    } else {
        echo json_encode(['error' => 'SQL error: ' . $conn->error]);
        exit();
    }

    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BulSU-MC-SDMS</title>
    <link rel="icon" href="assets/img/BMCLogo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/MainStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        function fetchTotalViolations() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'dashboard.php?action=get_violations', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    if (data.error) {
                        console.error(data.error);
                        document.getElementById('total-violations').innerText = 'Error loading data';
                    } else {
                        document.getElementById('total-violations').innerText = data.totalViolations;
                    }
                } else {
                    console.error('Error fetching data');
                    document.getElementById('total-violations').innerText = 'Error loading data';
                }
            };
            xhr.onerror = function() {
                console.error('Request failed');
                document.getElementById('total-violations').innerText = 'Error loading data';
            };
            xhr.send();
        }
        function fetchTotalStudentsViolations() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'dashboard.php?action=get_num_students_violations', true); // Corrected request URL
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    if (data.error) {
                        console.error(data.error);
                        document.getElementById('total-students-violations').innerText = 'Error loading data';
                    } else {
                        document.getElementById('total-students-violations').innerText = data.totalStudentsViolations;
                    }
                } else {
                    console.error('Error fetching data');
                    document.getElementById('total-students-violations').innerText = 'Error loading data';
                }
            };
            xhr.onerror = function() {
                console.error('Request failed');
                document.getElementById('total-students-violations').innerText = 'Error loading data';
            };
            xhr.send();
        }
        window.onload = function() {
            fetchTotalViolations();
            fetchTotalStudentsViolations();
        };
    </script>
    <style>
        body {
            background-image: url("assets/img/BMCLoginWP.png");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
<header class="header">
        <div class="logo-container">
            <img src="assets/img/BMCLogo.png" alt="Company Logo" class="logo">
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
                <h2 style="color:white;">Welcome back, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <div class="dashboardContainer">
                <div class="button-tiles">
                    <div class="button-tile">
                        <a href="RecentReports.php" class="link">
                            <div class="info-box">
                                <div class="info-box-icon">
                                    <img src="assets\img\report-icon.png" alt="" class="icons">
                                </div>
                                <div class="info-box-content">
                                    <h2 class="info-box-text">Number of Recorded Violation</h2>
                                    <h2 id="total-violations">Loading...</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="button-tile">
                        <a href="SearchStudents.php" class="link">
                            <div class="info-box">
                                <div class="info-box-icon">
                                    <img src="assets\img\id-icon.png" alt="" class="icons">
                                </div>
                                <div class="info-box-content">
                                    <h2 class="info-box-text">Number of Student with Violation</h2>
                                    <h2 id="total-students-violations">Loading...</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="dashboardContent2">
                    <div class="left">
                        <div class="tile1">
                            <div class="tile1-left">
                                <h2>MISSION</h2>
                                <p>The Bulacan State University exists to produce highly competent, ethical and service-oriented professionals that contribute to the sustainable socio-economic growth and development of the nation</p>
                            </div>
                            <div class="tile1-right">
                                <h2>VISION</h2>
                                <p>The Bulacan State University is a progressive knowledge-generating institution globally recognized for excellent instruction, pioneering research, and responsive community engagements</p>
                            </div>
                        </div>
                        <div class="tile2">
                            <div class="tile2-content">
                                <h2>SOAR BULSU</h2>
                                <div class="soarList">
                                    <li><span>S</span>ERVICE TO GOD AND COMMUNITY</li>
                                    <li><span>O</span>RDER AND PEACE</li>
                                    <li><span>A</span>SSURANCE OF QUALITY AND ACCOUNTABILITY</li>
                                    <li><span>R</span>ESPECT AND RESPONSIBILITY</li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>