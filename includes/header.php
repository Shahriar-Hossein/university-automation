<?php
if (!function_exists('get_base_url')) {
    // fallback if header included before bootstrap
    function get_base_url() { return ''; }
}
if (!isset($base_url)) {
    $base_url = get_base_url();
}
if (!isset($page_title)) {
    $page_title = 'Login';
}
if (!isset($banner_title)) {
    $banner_title = $page_title;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/styles.css">
</head>

<body>
    <div class="headers">
        <nav>
            <div class="container">
                <div class="navlist">
                    <div class="logo">Student Management</div>
                    <ul>
                        <li><a href="<?php echo $base_url; ?>/index.php#home">Home</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#about">About</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#features">Notice</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#contact">Contact</a></li>
                    </ul>
                    <div>
                        <a href="<?php echo $base_url; ?>/loginpanel.php"><button>Login</button></a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="banner">
            <div class="container">
                <h1><?php echo htmlspecialchars($banner_title); ?></h1>
