<?php
require_once '../includes/dbconnection.php';

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch payment details for the student
    $sql = "SELECT s.s_Name as student_name, p.total_amount, p.paid_amount, 
            (p.total_amount - p.paid_amount) AS due_amount
            FROM student_db s
            LEFT JOIN payments p ON s.s_ID = p.student_id
            WHERE s.s_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment_data = $result->fetch_assoc();
        echo json_encode($payment_data);
    } else {
        echo json_encode(null); // No payment data found
    }
}
