<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../teacherlogin.php');
    exit();
}

function getGrade($totalNumber) {
    if ($totalNumber >= 80 && $totalNumber <= 100) {
        return 'A+';
    } elseif ($totalNumber >= 75 && $totalNumber <= 79) {
        return 'A';
    } elseif ($totalNumber >= 70 && $totalNumber <= 74) {
        return 'A-';
    } elseif ($totalNumber >= 65 && $totalNumber <= 69) {
        return 'B+';
    } elseif ($totalNumber >= 60 && $totalNumber <= 64) {
        return 'B';
    } elseif ($totalNumber >= 55 && $totalNumber <= 59) {
        return 'B-';
    } elseif ($totalNumber >= 50 && $totalNumber <= 54) {
        return 'C+';
    } elseif ($totalNumber >= 45 && $totalNumber <= 49) {
        return 'C';
    } elseif ($totalNumber >= 40 && $totalNumber <= 44) {
        return 'D';
    } else {
        return 'F';
    }
}


// Get the teacher's ID from the session
$teacher_login_user = $_SESSION['teacher_login_id'];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $grades = $_POST['grades']; // This will be an associative array with student IDs as keys and grades as values
    $marks = $_POST['total_number'];

    // Prepare to save grades
    foreach ($marks as $student_id => $mark) {
        // Check if a grade already exists for this student
        $check_sql = "SELECT * FROM grades WHERE student_id = ? AND course_id = ? AND section_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("iii", $student_id, $course_id, $section_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing grade
            $update_sql = "UPDATE grades SET marks = ?, grade = ? WHERE student_id = ? AND course_id = ? AND section_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("isiii",$mark, getGrade($mark), $student_id, $course_id, $section_id);
            $update_stmt->execute();
        } else {
            // Insert new grade
            $insert_sql = "INSERT INTO grades (student_id, course_id, section_id, marks, grade) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iiiis", $student_id, $course_id, $section_id, $mark, getGrade($mark));
            $insert_stmt->execute();
        }
        
        // Mark the course as completed for this student if they have a passing grade
        if ($mark >= 40) { // Assuming any grade other than 'F' is considered passing
            $complete_sql = "UPDATE student_course SET status = 'completed' WHERE course_id = ? AND section_id = ? AND student_id = ?";
            $complete_stmt = $conn->prepare($complete_sql);
            $complete_stmt->bind_param("iii", $course_id, $section_id, $student_id);
            $complete_stmt->execute();
        }
    }

    // Redirect back to the grading page with a success message
    header("Location: ../pages/grading.php?success=Grades saved successfully.");
    exit();
}
