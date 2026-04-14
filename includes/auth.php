<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbconnection.php';

function get_base_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
    $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . ($base_path === '' || $base_path === '/' ? '' : $base_path);
}

function redirect_to($path)
{
    header('Location: ' . rtrim(get_base_url(), '/') . '/' . ltrim($path, '/'));
    exit();
}

?>
