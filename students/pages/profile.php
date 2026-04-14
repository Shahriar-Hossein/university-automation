<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';
if (!isset($_SESSION['student_login_id'])) {
    header('Location: ../studentlogin.php');
}
$student_login_user = $_SESSION['student_login_id'];

$student_sql = mysqli_query($conn, "SELECT * FROM `student_db` WHERE `s_ID` = '$student_login_user'");
$student_login_user_data = mysqli_fetch_assoc($student_sql);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile ||Students</title>
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
                <span class="text">Profile || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
            </div>
            <div class="main_workPanel" style="padding-bottom: 30px">
                <div class="main_workPanel_header">
                    <h3>Students's Profile</h3>
                </div>
                <div class="admin_monitor_add" style="position: relative;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto;">
                        <img src='<?= $student_login_user_data['s_Photo'] ?>' alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                    </div>
                    <div style="position: absolute; top:10px; right:10px;"><a href="<?= BASE_URL ?>edit_profile.php"><i class='bx bxs-edit' style='font-size: 32px;'></i></a></div>
                    <h2 style="margin-top: 20px;;">
                        <?php echo $student_login_user_data['s_Name']; ?>
                    </h2>

                    <table style="margin-top: 20px;;">
                        <tbody>

                            <tr>
                                <td>User Name:</td>
                                <td><?php echo $student_login_user_data['s_UserName']; ?></td>
                            </tr>
                            <tr>
                                <td>ID:</td>
                                <td><?php echo $student_login_user_data['s_ID']; ?></td>
                            </tr>
                            <tr>
                                <td>Email Id:</td>
                                <td><?php echo $student_login_user_data['s_Email']; ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth:</td>
                                <td><?php echo date('d-M-Y', strtotime($student_login_user_data['s_DateOfBirth'])); ?></td>
                            </tr>
                            <tr>
                                <td>Contact No.:</td>
                                <td><?php echo $student_login_user_data['s_ContactNo']; ?></td>
                            </tr>
                            <tr>
                                <td>Alt-Contact No.:</td>
                                <td><?php echo $student_login_user_data['s_AltContactNo']; ?></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td><?php echo $student_login_user_data['s_Address']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>