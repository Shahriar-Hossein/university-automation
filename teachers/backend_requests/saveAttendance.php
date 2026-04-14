<?php

session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../../teacherlogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $teacher_id = $_SESSION['teacher_login_id'];
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $date = $_POST['date'];
    $attendance_data = $_POST['attendance']; // Array of student_id => status (present/absent)

    // Validate the date
    if (strtotime($date) > time()) {
        die("The date cannot be in the future.");
    }

    // Loop through attendance data and insert into the database
    foreach ($attendance_data as $student_id => $status) {
        // Prevent SQL Injection
        $student_id = mysqli_real_escape_string($conn, $student_id);
        $status = mysqli_real_escape_string($conn, $status);

        // Check if the attendance record already exists for the student on the given date
        $check_sql = "
            SELECT id FROM attendances 
            WHERE student_id = $student_id AND course_id = $course_id AND section_id = $section_id AND date = '$date'
        ";
        $check_result = mysqli_query($conn, $check_sql);

        if ($check_result->num_rows > 0) {
            // Update existing record
            $update_sql = "
                UPDATE attendances 
                SET status = '$status' 
                WHERE student_id = $student_id AND course_id = $course_id AND section_id = $section_id AND date = '$date'
            ";
            mysqli_query($conn, $update_sql);
        } else {
            // Insert new record
            $insert_sql = "
                INSERT INTO attendances (student_id, course_id, section_id, date, status) 
                VALUES ($student_id, $course_id, $section_id, '$date', '$status')
            ";
            mysqli_query($conn, $insert_sql);
        }
    }

    // Provide feedback to the user
    header('Location: ../pages/attendance.php?success=true');
    exit();
} else {
    // Redirect to the attendance page if the request method is not POST
    header('Location: ../pages/attendance.php');
    exit();
}

