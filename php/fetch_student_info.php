<?php
require_once 'db.php'; // Adjust path as per your file structure

if (isset($_GET['student_number'])) {
    $student_number = $_GET['student_number'];

    // Fetch student information
    $sql_student = "SELECT student_number, name, gender, department FROM students WHERE student_number = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("s", $student_number);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();
    $student = $result_student->fetch_assoc();

    // Fetch associated reports
    $sql_reports = "SELECT violation, no_of_offense, detailed_report, date_of_violation, action_taken FROM reports WHERE student_name = ?";
    $stmt_reports = $conn->prepare($sql_reports);
    $stmt_reports->bind_param("s", $student['name']);
    $stmt_reports->execute();
    $result_reports = $stmt_reports->get_result();

    $reports = [];
    while ($row = $result_reports->fetch_assoc()) {
        $reports[] = $row;
    }

    echo json_encode(['student' => $student, 'reports' => $reports]);

    $stmt_student->close();
    $stmt_reports->close();
}
$conn->close();
?>
