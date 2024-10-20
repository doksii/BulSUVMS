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

// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "bulsuvms"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// number of students that is has reports
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_violations') {
    $sql = "SELECT COUNT(violation) AS total_unique_offenses FROM reports"; // Ensure 'reports' is the correct table name
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $totalViolations = $row['total_unique_offenses'];
        echo json_encode(['totalViolations' => $totalViolations]);
    } else {
        echo json_encode(['error' => 'SQL error: ' . $conn->error]);
        exit(); // Ensure you exit after sending an error response
    }

    $conn->close();
    exit(); // End the script after handling the AJAX request
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_num_students_violations') {
    $sql = "SELECT COUNT(DISTINCT student_name) AS total_students_with_violations FROM reports"; // Ensure 'reports' is the correct table name
    if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $totalStudentsViolations = $row['total_students_with_violations']; // Corrected variable name
        echo json_encode(['totalStudentsViolations' => $totalStudentsViolations]);
    } else {
        echo json_encode(['error' => 'SQL error: ' . $conn->error]);
        exit(); // Ensure you exit after sending an error response
    }

    $conn->close();
    exit(); // End the script after handling the AJAX request
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BulSUVMS</title>
    <link rel="icon" href="assets/img/BMCLogo.png" type="image/png">
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/css/MainStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
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

        // Fetch the total violations and students with violations when the page loads
        window.onload = function() {
            fetchTotalViolations();
            fetchTotalStudentsViolations();
        };
    </script>
    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-image: url("assets/img/BMCLoginWP.png");
            background-size: cover;
            background-position: center;
        }
        .WelcomeMessage {
            color: white;
        }
        .button-tiles {
            display: flex;
            flex-direction: row;
            padding: 10px;
        }
        .button-tile {
            height: 100px;
            width: 50%;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            margin: 10px;
            border: 2px solid rgba(255, 255, 255, 0);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(2px);
        }
        .link {
            text-decoration: none;
        }
        .info-box {
            display:flex;
            flex-direction: row;
            text-decoration: none;
            color: white;
            text-align: left;
            font-size: 16px;
        }
        .icons {
            width: 100px;
            height: 100px;
        }
        .info-box-content {   
            width: 100%;
            display:flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            margin: 10px;
        }
        .dashboardContent2 {
            height: 100%;
        }
        .left {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 300px;
        }

        .tile1 {
            /* flex: 1; */
            margin: 5px;
            letter-spacing: 2px;
            font-size: 18px;
            word-spacing: 3px;
            display: flex;
            flex-direction: row;
            height: 100%;
            width: 50%;
            padding: 0;
        }
        .tile1-left, .tile1-right {
            flex: 1;
            margin: 0;
            padding: 5px;
            border: 2px solid rgba(255, 255, 255, 0);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(2px);
            text-align: center;
            padding: 10px;
            color: white;
        }
        .tile1-left {
            margin-right: 10px;
        }
        .tile2 {
            /* flex: 1; */
            display: flex;
            justify-content: center;
            margin: 5px;
            border: 2px solid rgba(255, 255, 255, 0);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(2px);
            letter-spacing: 2px;
            height: 100%;
            text-align: center;
            width: 50%;
            padding: 0;
            color: white;
        }
        .tile2-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            margin: 0;
            text-align: center;
            padding: 10px;
        }
        .soarList {
            align-self: center;
            margin: 5px;
        }
        .tile2 li {
            padding: 5px;
            text-align: left;
            list-style: none;
            font-size: 18px;
        }
        .tile2 span {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-weight: 600;
            font-size: 25px;
            text-shadow: 1px 2px 4px white;
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
                <h2>Welcome back, <?php echo $_SESSION['display_name']; ?>!</h2>
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
                                    <h2 id="total-violations">Loading...</h2> <!-- Placeholder -->
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
                                    <h2 id="total-students-violations">Loading...</h2> <!-- Placeholder for total students with violations -->
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
                    <!-- <div class="tile3">
                        <h2>GOALS</h2>
                        <li>Quality and Excellence. Promoting quality and relevant educational programs that meet international standards.</li>
                        <li>Relevance and Responsiveness. Generation and dissemination of knowledge in the broad range of disciplines relevant and responsive to the dynamically changing domestic and international environments.</li>
                        <li>Access and Equity. Broadening the access of deserving and qualified students to educational opportunities.</li>
                        <li>Efficiency and Effectiveness. Optimizing of social, institutional and individual returns and benefits derived from the utilization of higher education resources.</li>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>