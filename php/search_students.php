<?php
require_once 'db.php'; // Adjust path as per your file structure

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $sql = "SELECT student_number, name FROM students WHERE student_number LIKE ? OR name LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="search-item" data-student-number="' . $row['student_number'] . '">' . $row['name'] . ' (' . $row['student_number'] . ')</div>';
        }
    } else {
        echo '<div class="search-item">No students found.</div>';
    }

    $stmt->close();
}
$conn->close();
?>
