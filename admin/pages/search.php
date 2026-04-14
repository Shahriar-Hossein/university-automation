<?php
session_start();
require_once('../includes/dbconnection.php');
if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

?>
?>
<?php
    $page_title = 'Search ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
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

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>