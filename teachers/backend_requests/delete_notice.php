<?php
// delete_notice.php

session_start();
require_once('../includes/dbconnection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_id = $_POST['notice_id'];

    // Sanitize input
    $notice_id = (int) $notice_id;

    // Delete the notice from the database
    $delete_sql = "
        DELETE FROM notices
        WHERE id = $notice_id
    ";

    if (mysqli_query($conn, $delete_sql)) {
        // Redirect back with success message
        $_SESSION['success'] = 'Notice deleted successfully!';
        header('Location: ../pages/manageNotice.php');
        exit();
    } else {
        // Handle the error
        $_SESSION['error'] = 'Failed to delete notice. Please try again.';
        header('Location: ../pages/manageNotice.php');
        exit();
    }
} else {
    // Redirect if accessed directly
    header('Location: ../pages/manageNotice.php');
    exit();
}
