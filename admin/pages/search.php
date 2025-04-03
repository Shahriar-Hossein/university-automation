<?php
session_start();
require_once('../includes/dbconnection.php');
if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search ||Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once('../includes/sidebar.php'); ?>
    <section class="home-section">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">Search || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
            </div>
            <div class="main_workPanel">
                <div class="main_workPanel_header">
                    <h3>Search Student</h3>
                </div>
                <form class="search-form">
                    <h3>Search Student:</h3>
                    <input type="text" placeholder="Search by Student ID" class="search-input">
                    <button type="submit" class="search-button">Search</button>
                </form>

            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>