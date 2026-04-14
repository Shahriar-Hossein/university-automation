<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/login_handler.php';

$base_url = get_base_url();

if (isset($_SESSION['admin_login_id'])) {
    redirect_to('admin/dashboard.php');
}

$errors = handle_login([
    'submit_name' => 'ALOGIN',
    'post_id' => 'AID',
    'post_pw' => 'APASSWORD',
    'table' => 'admin_db',
    'id_col' => 'Id',
    'pw_col' => 'password',
    'session_key' => 'admin_login_id',
    'session_store_col' => 'Id',
    'redirect' => 'admin/dashboard.php',
    'id_not_found_message' => 'Wrong ID...!'
]);

$id_error = $errors['id'] ?? null;
$password_error = $errors['password'] ?? null;

$page_title = 'Admin Login';
$banner_title = 'Login as Admin';
$form_action = $base_url . '/adminlogin.php';
$id_name = 'AID';
$pw_name = 'APASSWORD';
$id_label = 'Enter your ID';
$submit_name = 'ALOGIN';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/login_form.php';
require_once __DIR__ . '/includes/footer.php';
