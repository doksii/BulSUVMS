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
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/css/MainStyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add some basic styling for the search box and results */
        #search-results {
            border: .2px solid #ddd;
            width: 500px;
            max-height: 100px;
            overflow-y: auto;
            margin-left: auto;
            margin-right: auto;
        }
        .search-item {
            padding: 10px;
            cursor: pointer;
        }
        .search-item:hover {
            background-color: #f0f0f0;
        }
        #student_search {
            width: 500px;
            padding: 5px;
            box-sizing: border-box;
        }
        .scroll-container {
            max-height: 100vh; /* Set max height for the container */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-top: 20px;
            /* border: 1px solid #ccc; */
            padding: 10px;
            box-sizing: border-box;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#student_search').on('input', function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'php/search_students.php',
                        method: 'POST',
                        data: {query: query},
                        success: function(data) {
                            $('#search-results').html(data);
                        }
                    });
                } else {
                    $('#search-results').html('');
                }
            });

            $(document).on('click', '.search-item', function() {
                let studentNumber = $(this).data('student-number');
                let studentName = $(this).text();
                $('#student_search').val(studentName);
                $('#student_number').val(studentNumber);
                $('#search-results').html('');
            });
        });
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'success') {
                    alert('Report created successfully.');
                } else if (status === 'error') {
                    alert('There was an error creating the report. Please try again.');
                } else if (status === 'student_not_found') {
                    alert('Student not found. Please check the student number.');
                }
            }
        };
    </script>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="assets\img\BMCLogo.png" alt="Company Logo" class="logo">
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
            <!-- Content of the dashboard page goes here -->
            <h1>Welcome to the CreateReport!</h1>
            <p>This is a simple CreateReport page.</p>
            <div class="scroll-container">
                <form action="php/createreport_process.php" method="post">
                    <label for="student_search">Search Student:</label><br>
                    <input type="text" id="student_search" name="student_search" autocomplete="off" placeholder="Name or Student number">
                    <div id="search-results"></div><br>

                    <input type="hidden" id="student_number" name="student_number">

                    <label for="violation">Violation:</label><br>
                    <select id="violation" name="violation" required>
                        <option value="Light Offenses: Littering or distribution of unauthorized printed material">Light Offenses: Littering or distribution of unauthorized printed material</option>
                        <option value="Light Offenses: Vandalism or unauthorized posting of printed materials">Light Offenses: Vandalism or unauthorized posting of printed materials</option>
                        <option value="Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities">Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities</option>
                        <option value="Light Offenses: Unauthorized solicitation of funds or selling of any ticket">Light Offenses: Unauthorized solicitation of funds or selling of any ticket</option>
                        <option value="Less Grave Offenses: Smoking, gambling or being under the influence of alcohol within the university premises">Less Grave Offenses: Smoking, gambling or being under the influence of alcohol within the university premises</option>
                        <option value="Less Grave Offenses: Malicious or unfounded accusation towards any member of the academic community">Less Grave Offenses: Malicious or unfounded accusation towards any member of the academic community</option>
                        <option value="Less Grave Offenses: Deception, Impersonation, or Fraud">Less Grave Offenses: Deception, Impersonation, or Fraud</option>
                        <option value="Less Grave Offenses: Disrespectful behavior in words and in deeds or refusal to comply with directions of the University officials and employees acting in the performance of their duties">Less Grave Offenses: Disrespectful behavior in words and in deeds or refusal to comply with directions of the University officials and employees acting in the performance of their duties</option>
                        <option value="Less Grave Offenses: Damage or unauthorized presence in or use of University premises, facilities or property, in violation of posted signs, when closed, or after normal operating hours">Less Grave Offenses: Damage or unauthorized presence in or use of University premises, facilities or property, in violation of posted signs, when closed, or after normal operating hours</option>
                        <option value="Grave Offenses: Theft, attempted theft, and/or unauthorized possession or use of property/services belonging to the University or a member of the University community">Grave Offenses: Theft, attempted theft, and/or unauthorized possession or use of property/services belonging to the University or a member of the University community</option>
                        <option value="Grave Offenses: Indecency in any form of obscene or lewd behavior (necking, petting or torrid kissing or other sexual act) inside the university premises">Grave Offenses: Indecency in any form of obscene or lewd behavior (necking, petting or torrid kissing or other sexual act) inside the university premises</option>
                        <option value="Grave Offenses: Physical/verbal/sexual/mental/emotional abuse, threat, harassment, cyber bullying, hazing, coercion and/or other conduct that threatens or endangers the health or safety of any person">Grave Offenses: Physical/verbal/sexual/mental/emotional abuse, threat, harassment, cyber bullying, hazing, coercion and/or other conduct that threatens or endangers the health or safety of any person</option>
                        <option value="Grave Offenses: Possession, use, sale or purchase of any illegal drugs inside the university premises">Grave Offenses: Possession, use, sale or purchase of any illegal drugs inside the university premises</option>
                        <option value="Grave Offenses: Carrying of firearms and other weapons within the University campuses and premises">Grave Offenses: Carrying of firearms and other weapons within the University campuses and premises</option>
                        <option value="Dishonesty on Academic Pursuits: Academic misconduct: Cheating">Dishonesty on Academic Pursuits: Academic misconduct: Cheating</option>
                        <option value="Dishonesty on Academic Pursuits: Academic misconduct: Plagiarism in theses, literary and creative works">Dishonesty on Academic Pursuits: Academic misconduct: Plagiarism in theses, literary and creative works</option>
                        <option value="Dishonesty on Academic Pursuits: Falsification or forging of academic records and official documents">Dishonesty on Academic Pursuits: Falsification or forging of academic records and official documents</option>
                    </select><br><br>
                    <label for="no_of_offense">Number of Offenses:</label><br>
                    <select id="no_of_offense" name="no_of_offense" required>
                        <option value="1st offense">1st Offense</option>
                        <option value="2nd offense">2nd Offense</option>
                        <option value="3rd offense">3rd Offense</option>
                        <option value="4th offense">4th Offense</option>
                        <option value="5th offense">5th Offense</option>
                    </select><br><br>
                    <label for="detailed_report">Report Summary:</label><br>
                    <textarea id="detailed_report" name="detailed_report" rows="4" required></textarea><br><br>

                    <label for="date_of_violation">Date of Violation:</label><br>
                    <input type="date" id="date_of_violation" name="date_of_violation" required><br><br>

                    <label for="action_taken">Action Taken:</label><br>
                    <input type="text" id="action_taken" name="action_taken" required><br><br>

                    <!-- Hidden field to store the user ID -->
                    <input type="hidden" name="created_by" value="<?php echo $_SESSION['display_name']; ?>">

                    <input type="submit" value="Create Report">
                </form>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
<!-- asdasd -->