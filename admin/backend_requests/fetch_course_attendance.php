<?php
require_once '../includes/dbconnection.php';

$student_id = $_GET['student_id'] ?? null;

if ($student_id) {
    // Correct SQL query to fetch detailed attendance per course for the student
    $sql = "SELECT course_db.c_title as course_name, teacher_db.t_Name AS teacher_name, sections.section, 
            COUNT(CASE WHEN attendances.status = 1 THEN 1 END) AS present, 
            COUNT(CASE WHEN attendances.status = 0 THEN 1 END) AS absent
            FROM attendances
            JOIN course_db ON attendances.course_id = course_db.c_ID
            JOIN sections ON attendances.section_id = sections.id
            JOIN teacher_db ON sections.teacher_id = teacher_db.t_ID
            WHERE attendances.student_id = ?
            GROUP BY course_db.c_title, teacher_db.t_Name, sections.section";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result and convert to JSON format
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return the result as JSON
    echo json_encode($data);
}
