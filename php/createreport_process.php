<?php
session_start();
require_once 'db.php'; // Adjust path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = $_POST['student_number'];
    $violation = $_POST['violation'];
    $no_of_offense = $_POST['no_of_offense'];
    $detailed_report = $_POST['detailed_report'];
    $date_of_violation = $_POST['date_of_violation'];
    $action_taken = $_POST['action_taken'];
    $created_by = $_SESSION['display_name']; // Assuming display_name is stored in session

    // Validate if student exists and get the student name
    $sql = "SELECT id, name FROM students WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $student_id = $row['id'];
        $student_name = $row['name'];

        // Insert the report into the reports table
        $sql_insert = "INSERT INTO reports (id, student_name, violation, no_of_offense, detailed_report, date_of_violation, action_taken, created_by)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("isssssss", $id, $student_name, $violation, $no_of_offense, $detailed_report, $date_of_violation, $action_taken, $created_by);

        if ($stmt_insert->execute()) {
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

    $stmt->close();
    $stmt_insert->close();
    $conn->close();
}
?>
