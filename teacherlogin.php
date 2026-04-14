<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/login_handler.php';

$base_url = get_base_url();

if (isset($_SESSION['teacher_login_id'])) {
    redirect_to('teachers/dashboard.php');
}

$errors = handle_login([
    'submit_name' => 'login',
    'post_id' => 'username',
    'post_pw' => 'password',
    'table' => 'teacher_db',
    'id_col' => 't_UserName',
    'pw_col' => 't_Password',
    'session_key' => 'teacher_login_id',
    'session_store_col' => 't_ID',
    'session_extra' => ['teacher_name' => 't_Name'],
    'redirect' => 'teachers/dashboard.php',
    'id_not_found_message' => 'No User Found...!'
]);

$id_error = $errors['id'] ?? null;
$password_error = $errors['password'] ?? null;

$page_title = 'Teacher Login';
$banner_title = 'Login as Teacher';
$form_action = $base_url . '/teacherlogin.php';
$id_name = 'username';
$pw_name = 'password';
$id_label = 'Enter your username';
$submit_name = 'login';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/login_form.php';
require_once __DIR__ . '/includes/footer.php';
