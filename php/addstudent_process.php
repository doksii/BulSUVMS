<?php
session_start();
require_once 'db.php';

// Get the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $student_number = $_POST['student_number'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $year_lvl = $_POST['year_lvl']; // New field for year level

    // Check if student number already exists
    $sql_check = "SELECT id FROM students WHERE student_number = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $student_number);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Duplicate student number
        header("Location: ../AddStudents.php?status=error");
    } else {
        // Prepare and bind SQL statement to insert into `students` table
        $sql_insert = "INSERT INTO students (name, student_number, gender, department, year_lvl) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $name, $student_number, $gender, $department, $year_lvl);

        // Execute insertion
        if ($stmt_insert->execute()) {
            header("Location: ../AddStudents.php?status=success");
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
        // Close statement
        $stmt_insert->close();
    }

    // Close connection
    $stmt_check->close();
    $conn->close();
}
?>
