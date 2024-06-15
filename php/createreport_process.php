<?php
session_start();
require_once 'db.php'; // Adjust path as per your file structure


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_search_by = $_POST['student_search_by'];
    $student_query = $_POST['student_query'];
    $violation = $_POST['violation'];
    $no_of_offense = $_POST['no_of_offense'];
    $detailed_report = $_POST['detailed_report'];
    $date_of_violation = $_POST['date_of_violation'];
    $action_taken = $_POST['action_taken'];
    $created_by = $_SESSION['display_name']; // Get the user display name from the session

    // Prepare and execute the SQL statement to fetch the student information
    if ($student_search_by == 'student_number') {
        $sql = "SELECT name FROM students WHERE student_number = ?";
    } elseif ($student_search_by == 'name') {
        $sql = "SELECT name FROM students WHERE name = ?";
    } else {

        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if student is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_name = $row['name'];

        // Prepare and bind SQL statement to insert into `reports` table
        $sql_insert = "INSERT INTO reports (student_name, violation, no_of_offense, detailed_report, date_of_violation, action_taken, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssss", $student_name, $violation, $no_of_offense, $detailed_report, $date_of_violation, $action_taken, $created_by);

        // Execute insertion
        if ($stmt->execute()) {
            // Success
            header("Location: ../CreateReport.php?status=success");
        } else {
            // Error
            header("Location: ../CreateReport.php?status=error");
        }
    } else {
        // Student not found
        header("Location: ../CreateReport.php?status=student_not_found");
    }
    // Close connection
    $stmt->close();
    $conn->close();
}

?>
