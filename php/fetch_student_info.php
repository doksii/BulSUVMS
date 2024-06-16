<?php
    // Replace with your database credentials
require_once 'db.php'; // Adjust path as per your file structure
    
    $student_number = $_GET['student_number']; // Assuming student_number is passed via GET parameter
    
    $sql = "SELECT * FROM students WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($student_result->num_rows > 0) {
        $student_info = $student_result->fetch_assoc();

        echo "<h2>Student Information</h2>";
        echo "<p>Student Number: " . $student_info["student_number"] . "</p>";
        echo "<p>Name: " . $student_info["name"] . "</p>";
        echo "<p>Gender: " . $student_info["gender"] . "</p>";
        echo "<p>Department: " . $student_info["department"] . "</p>";

        // Fetch associated reports
        $sql = "SELECT report_id, violation, detailed_report, date_of_violation, action_taken, created_by FROM reports WHERE id = id";
        $report_result = $conn->query($sql);

        if ($report_result->num_rows > 0) {
            echo "<h2>Associated Reports</h2>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>Incident no.</th>
                            <th>Violation</th>
                            <th>Detailed Report</th>
                            <th>Date of Violation</th>
                            <th>Action Taken</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>";
            while($report_row = $report_result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $report_row["id"]. "</td>
                        <td>" . $report_row["violation"]. "</td>
                        <td>" . $report_row["detailed_report"]. "</td>
                        <td>" . $report_row["date_of_violation"]. "</td>
                        <td>" . $report_row["action_taken"]. "</td>
                        <td>" . $report_row["created_by"]. "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No reports found for this student.</p>";
        }
    } else {
        echo "<p>Student not found.</p>";
    }

    // Close connection
    $conn->close();
} else {
    echo "<p>Invalid request.</p>";
}
?>
