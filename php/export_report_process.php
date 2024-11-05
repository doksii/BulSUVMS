<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once '../vendor/autoload.php'; // Make sure you include the autoload.php

// Check if report IDs and password are set
if (isset($_POST['report_ids'])) {
    $report_ids = json_decode($_POST['report_ids'], true);
    
    class MYPDF extends TCPDF {

        // Page header
        public function Header() {
            // Use absolute path to the header image
            $header_image_file = realpath('..\assets\img\header.jpg'); // Ensure correct path
            if ($header_image_file) {
                $this->Image($header_image_file, 0, 0, 210, 50, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false); // 210 mm width
            } else {
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(0, 10, 'Header Image Not Found', 0, 1, 'C');
            }
            $this->Ln(30); // Adjust space if needed
        }
    
        // Page footer
        public function Footer() {
            // Absolute path to the footer image
            $footer_image_file = realpath('../assets/img/footer.jpg');
            if ($footer_image_file) {
                $this->SetY(-30); // Position footer 30mm from bottom
                $this->Image($footer_image_file, 0, $this->GetY(), 210, 30, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false); // 210 mm width
            } else {
                // Debugging: check if the path is incorrect
                $this->SetFont('helvetica', 'B', 10);
                $this->SetY(-15); // Position for footer text
                $this->Cell(0, 10, 'Footer Image Not Found', 0, 1, 'C');
            }
            $this->SetY(-15); // Position at 15 mm from bottom
            $this->SetFont('helvetica', 'I', 10);
            $this->Cell(20, 5, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
        }
    }

    // Create new PDF document using the extended class with header and footer
    $pdf = new MYPDF();

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Reports');
    $pdf->SetSubject('Reports PDF');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');    
    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 55, PDF_MARGIN_RIGHT); // Set left and right margins
    $pdf->SetHeaderMargin(0); // Set header margin height to 50 mm
    $pdf->SetFooterMargin(30); // Set footer margin height to 30 mm
// Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 30);
    
    // Add a page
    $pdf->AddPage();

    // Set font for the title
    $pdf->SetFont('helvetica', 'B', 16); // Bold font for the title
    $pdf->Cell(0, 5, 'Bulacan State University Meneses Campus', 0, 1, 'C'); // Centered title
    // Set font for subtitles or additional headers
    $pdf->SetFont('helvetica', 'I', 12); // Italic font for a subtitle
    $pdf->Cell(0, 10, 'Student Violation Records', 0, 1, 'C'); // Centered subtitle
    $pdf->Ln(5); // Line break for spacing
    // Add a line for separation
    $pdf->SetLineWidth(0.5); // Set thickness to 0.5mm
    $pdf->Cell(0, 0, '', 'T', 1); // Horizontal line
    // $pdf->Ln(5); // Line break for spacing

    $pdf->SetLineWidth(0.2); // Set thickness to 0.5mm
    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Fetch report data from the database
    require_once 'db.php';
    $ids = implode(',', array_map('intval', $report_ids)); // Prevent SQL injection
    $sql = "SELECT * FROM reports WHERE id IN ($ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Add content to the PDF
        while ($row = $result->fetch_assoc()) {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(32, 1, 'Student Name: ', 0, 0); // Label for Student Name
            $pdf->SetFont('helvetica', '', 12); // Set font to normal for the value
            $pdf->Cell(0, 1, $row['student_name'], 0, 1); // Output Student Name value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(22, 1, 'Report ID: ', 0, 0); // Label for Report ID
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->Cell(0, 1, $row['id'], 0, 1); // Output Report ID value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(30, 1, 'Date Created: ', 0, 0); // Label for Date Created
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->Cell(0, 1, $row['created_at'], 0, 1); // Output Date Created value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(37, 1, 'Date of violation: ', 0, 0); // Label for Violation
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->Cell(0, 1, $row['date_of_violation'], 0, 1); // Output Violation value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(20, 1, 'Violation: ', 0, 0); // Label for Violation
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->MultiCell(0, 1, $row['violation'], 0, 1); // Output Violation value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(30, 1, 'No. of offense: ', 0, 0); // Label for Violation
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->Cell(0, 1, $row['no_of_offense'], 0, 1); // Output Violation value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(28, 1, 'Action taken: ', 0, 0); // Label for Violation
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->MultiCell(0, 1, $row['action_taken'], 0, 'L'); // Output Violation value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(36, 1, 'Report summary: ', 0, 0); // Label for Violation
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->MultiCell(0, 1, $row['detailed_report'], 0, 'L'); // Output Violation value

            $pdf->SetFont('helvetica', 'B', 12); // Set font back to bold
            $pdf->Cell(25, 1, 'Created By: ', 0, 0); // Label for Created By
            $pdf->SetFont('helvetica', '', 12); // Set font to normal
            $pdf->Cell(0, 1, $row['created_by'], 0, 1); // Output Created By value

            $pdf->Ln(5); // Line break for spacing
            $pdf->Cell(0, 0, '', 'T', 1); // Horizontal line

        }
    } else {
        $pdf->Cell(0, 10, 'No reports found', 0, 1);
    }

    // Close and output PDF document
    $pdf->Output('BulSUSDMS-reports.pdf', 'D'); // 'D' for download
} else {
    echo 'Invalid request.';
}
?>