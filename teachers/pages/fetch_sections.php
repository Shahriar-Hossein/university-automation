<?php
// Include the database connection
require_once __DIR__ . '/../../dbconnection.php';

// Check if the course_id parameter is passed
if (isset($_GET['course_id'])) {
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);

    // Fetch sections based on the course ID, including section id
    $sections_sql = "SELECT id, section FROM sections WHERE course_id = '$course_id'";
    $sections_result = mysqli_query($conn, $sections_sql);

    $sections = [];
    if ($sections_result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($sections_result)) {
            // Return both section ID and section name
            $sections[] = ['id' => $row['id'], 'name' => $row['section']];
        }
    }

    // Return sections as a JSON response
    header('Content-Type: application/json');
    echo json_encode(['sections' => $sections]);
}
