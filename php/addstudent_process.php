<?php
// Replace with your database credentials
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = ""; // default password for XAMPP
$dbname = "user_registered";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$name = $_POST['name'];
$student_number = $_POST['student_number'];
$gender = $_POST['gender'];
$department = $_POST['department'];

// Prepare and bind SQL statement to insert into `students` table
$sql_insert = "INSERT INTO students (name, student_number, gender, department) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ssss", $name, $student_number, $gender, $department);

// Execute insertion
if ($stmt_insert->execute()) {
    echo "Student added successfully!";
} else {
    echo "Error: " . $sql_insert . "<br>" . $conn->error;
}

// Close statement and connection
$stmt_insert->close();
$conn->close();
?>