<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/login_handler.php';

$base_url = get_base_url();

if (isset($_SESSION['student_login_id'])) {
    redirect_to('students/dashboard.php');
}

$errors = handle_login([
    'submit_name' => 'SLOGIN',
    'post_id' => 'SID',
    'post_pw' => 'SPASSWORD',
    'table' => 'student_db',
    'id_col' => 's_ID',
    'pw_col' => 's_Password',
    'session_key' => 'student_login_id',
    'session_store_col' => 's_ID',
    'redirect' => 'students/dashboard.php',
    'id_not_found_message' => 'Wrong ID...!'
]);

$id_error = $errors['id'] ?? null;
$password_error = $errors['password'] ?? null;

$page_title = 'Student Login';
$banner_title = 'Login as Student';
$form_action = $base_url . '/studentlogin.php';
$id_name = 'SID';
$pw_name = 'SPASSWORD';
$id_label = 'Enter your ID';
$submit_name = 'SLOGIN';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/login_form.php';
require_once __DIR__ . '/includes/footer.php';
