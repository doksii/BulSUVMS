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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        textarea {
            resize: none;
            overflow-wrap: break-word;
            white-space: pre-wrap;
            vertical-align: top;
            height: auto;
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 10px;
        }
    </style>
    <script>
        $(document).ready(function() {
            function debounce(func, delay) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            }

            $('#student_search').on('input', debounce(function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'php/search_students.php',
                        method: 'POST',
                        data: {query: query},
                        success: function(data) {
                            $('#search-results').html(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('AJAX error: ', textStatus, errorThrown);
                        }
                    });
                } else {
                    $('#search-results').html('');
                }
            }, 300));

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
                    window.location.href = "RecentReports.php";
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
                <h2>Welcome to Create Report, <?php echo $_SESSION['display_name']; ?>!</h2>
            </div>
            <div class="CreateReportContent">
                <div class="ReportFormContainer">
                    <form class="CreateReportForm" action="php/createreport_process.php" method="post">
                        <div class="ReportForm">
                            <div class="LeftSideForm">
                                <label for="student_search">Search Student:</label>
                                <input type="text" id="student_search" name="student_search" autocomplete="off" placeholder="Name or Student number">
                                <div id="search-results" class="search-results"></div>

                                <input type="hidden" id="student_number" name="student_number">
                            </div>
                            <div class="RightSideForm">
                                <label for="violation">Violation:</label>
                                <select id="violation" name="violation" required>
                                    <option value="" disabled selected>--Select an violation--</option>
                                    <option class="LO" value="Light Offenses: Littering or distribution of unauthorized printed material">Light Offenses: Littering or distribution of unauthorized printed material</option>
                                    <option class="LO" value="Light Offenses: Vandalism or unauthorized posting of printed materials">Light Offenses: Vandalism or unauthorized posting of printed materials</option>
                                    <option class="LO" value="Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities">Light Offenses: Disturbance or disruption of the educational environment, classes or any education related programs or activities</option>
                                    <option class="LO" value="Light Offenses: Unauthorized solicitation of funds or selling of any ticket">Light Offenses: Unauthorized solicitation of funds or selling of any ticket</option>
                                    <option class="LGO" value="Less Grave Offenses: Smoking, gambling or being under the influence of alcohol within the university premises">Less Grave Offenses: Smoking, gambling or being under the influence of alcohol within the university premises</option>
                                    <option class="LGO" value="Less Grave Offenses: Malicious or unfounded accusation towards any member of the academic community">Less Grave Offenses: Malicious or unfounded accusation towards any member of the academic community</option>
                                    <option class="LGO" value="Less Grave Offenses: Deception, Impersonation, or Fraud">Less Grave Offenses: Deception, Impersonation, or Fraud</option>
                                    <option class="LGO" value="Less Grave Offenses: Disrespectful behavior in words and in deeds or refusal to comply with directions of the University officials and employees acting in the performance of their duties">Less Grave Offenses: Disrespectful behavior in words and in deeds or refusal to comply with directions of the University officials and employees acting in the performance of their duties</option>
                                    <option class="LGO" value="Less Grave Offenses: Damage or unauthorized presence in or use of University premises, facilities or property, in violation of posted signs, when closed, or after normal operating hours">Less Grave Offenses: Damage or unauthorized presence in or use of University premises, facilities or property, in violation of posted signs, when closed, or after normal operating hours</option>
                                    <option class="GO" value="Grave Offenses: Theft, attempted theft, and/or unauthorized possession or use of property/services belonging to the University or a member of the University community">Grave Offenses: Theft, attempted theft, and/or unauthorized possession or use of property/services belonging to the University or a member of the University community</option>
                                    <option class="GO" value="Grave Offenses: Indecency in any form of obscene or lewd behavior (necking, petting or torrid kissing or other sexual act) inside the university premises">Grave Offenses: Indecency in any form of obscene or lewd behavior (necking, petting or torrid kissing or other sexual act) inside the university premises</option>
                                    <option class="GO" value="Grave Offenses: Physical/verbal/sexual/mental/emotional abuse, threat, harassment, cyber bullying, hazing, coercion and/or other conduct that threatens or endangers the health or safety of any person">Grave Offenses: Physical/verbal/sexual/mental/emotional abuse, threat, harassment, cyber bullying, hazing, coercion and/or other conduct that threatens or endangers the health or safety of any person</option>
                                    <option class="GO" value="Grave Offenses: Possession, use, sale or purchase of any illegal drugs inside the university premises">Grave Offenses: Possession, use, sale or purchase of any illegal drugs inside the university premises</option>
                                    <option class="GO" value="Grave Offenses: Carrying of firearms and other weapons within the University campuses and premises">Grave Offenses: Carrying of firearms and other weapons within the University campuses and premises</option>
                                    <option class="DAP" value="Dishonesty on Academic Pursuits: Academic misconduct: Cheating">Dishonesty on Academic Pursuits: Academic misconduct: Cheating</option>
                                    <option class="DAP" value="Dishonesty on Academic Pursuits: Academic misconduct: Plagiarism in theses, literary and creative works">Dishonesty on Academic Pursuits: Academic misconduct: Plagiarism in theses, literary and creative works</option>
                                    <option class="DAP" value="Dishonesty on Academic Pursuits: Falsification or forging of academic records and official documents">Dishonesty on Academic Pursuits: Falsification or forging of academic records and official documents</option>
                                </select>

                                <div class="TwoFill">
                                    <div class="Fill1">
                                        <label for="no_of_offense">Number of Offenses:</label>
                                        <select id="no_of_offense" name="no_of_offense" required>
                                        <option value="" disabled selected>--Select no. of offense--</option>
                                            <option value="1st offense">1st Offense</option>
                                            <option value="2nd offense">2nd Offense</option>
                                            <option value="3rd offense">3rd Offense</option>
                                            <option value="Other">Others(Specify on report summary)</option>
                                        </select>
                                    </div>
                                    <div class="Fill2">
                                        <label for="date_of_violation">Date of Violation:</label>
                                        <input type="date" id="date_of_violation" name="date_of_violation" required>
                                    </div>
 
                                </div>
                                
                                <label for="action_taken">Action Taken:</label>
                                <input type="text" id="action_taken" name="action_taken" required>

                                <label for="detailed_report">Report Summary:</label>
                                <textarea class="Summary" id="detailed_report" name="detailed_report" rows="5" cols="23"></textarea>

                                <input type="hidden" name="created_by" value="<?php echo $_SESSION['display_name']; ?>">
                            </div>
                        </div>
                        <button type="submit">Create Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>