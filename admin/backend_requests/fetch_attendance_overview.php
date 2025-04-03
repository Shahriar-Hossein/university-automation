<?php
require_once '../includes/dbconnection.php';

$student_id = $_GET['student_id'] ?? null;

if ($student_id) {
    // Query to get the overall attendance for the student
    $sql = "SELECT student_id, s_Name AS student_name, COUNT(CASE WHEN status = 1 THEN 1 END) AS present, COUNT(CASE WHEN status = 0 THEN 1 END) AS absent
            FROM attendances 
            JOIN student_db ON attendances.student_id = student_db.s_ID 
            WHERE student_id = ?
            GROUP BY student_id, s_Name";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

