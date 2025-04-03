<?php
// edit_notice.php

session_start();
require_once('../includes/dbconnection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_id = $_POST['notice_id'];
    $notice_title = $_POST['notice_title'];
    $notice_message = $_POST['notice_message'];

    // Sanitize input
    $notice_title = mysqli_real_escape_string($conn, $notice_title);
    $notice_message = mysqli_real_escape_string($conn, $notice_message);

    // Update the notice in the database
    $update_sql = "
        UPDATE notices
        SET 
            title = '$notice_title',
            message = '$notice_message'
        WHERE
            id = $notice_id
    ";

    if (mysqli_query($conn, $update_sql)) {
        // Redirect back with success message
        $_SESSION['success'] = 'Notice updated successfully!';
        header('Location: ../pages/manageNotice.php');
        exit();
    } else {
        // Handle the error
        $_SESSION['error'] = 'Failed to update notice. Please try again.';
        header('Location: ../pages/manageNotice.php');
        exit();
    }
} else {
    // Redirect if accessed directly
    header('Location: ../pages/manageNotice.php');
    exit();
}
