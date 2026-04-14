<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../teacherlogin.php');
    exit();
}

$teacher_login_user = $_SESSION['teacher_login_id'];

// Fetch teacher's information from the database
$teacher_sql = "SELECT * FROM teacher_db WHERE t_ID = '$teacher_login_user'";
$teacher_result = mysqli_query($conn, $teacher_sql);

if ($teacher_result->num_rows > 0) {
    $teacher = mysqli_fetch_assoc($teacher_result);
} else {
    die("Error: Unable to fetch teacher's information.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile ||Teachers</title>
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
                <span class="text">Profile || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
            </div>
            <div class="main_workPanel" style="padding-bottom: 30px">
                <div class="main_workPanel_header">
                    <h3>Teacher's Profile</h3>
                </div>
                <div class="admin_monitor_add" style="position: relative;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto;">
                        <img src=<?=$teacher['t_ProfilePic'] ?> alt="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                    </div>
                    <div style="position: absolute; top:10px; right:10px;">
                        <a href="<?= BASE_URL ?>teachers/pages/edit_profile.php">
                            <i class='bx bxs-edit' style='font-size: 32px;'></i>
                        </a>
                    </div>
                    <h2 style="margin-top: 20px;"><?=$teacher['t_Name'] ?></h2>

                    <table style="margin-top: 20px;">
                        <tbody>
                            <tr>
                                <td>Full Name:</td>
                                <td><?= $teacher['t_Name'] ?></td>
                            </tr>
                            <tr>
                                <td>User Name:</td>
                                <td><?= $teacher['t_UserName']  ?></td>
                            </tr>
                            <tr>
                                <td>ID:</td>
                                <td><?= $teacher['t_ID'] ?></td>
                            </tr>
                            <tr>
                                <td>Email Id:</td>
                                <td><?= $teacher['t_Email'] ?></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td><?= $teacher['t_department'] ?></td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td><?= $teacher['t_designation'] ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth:</td>
                                <td><?= $teacher['t_DateOfBirth'] ?></td>
                            </tr>
                            <tr>
                                <td>Contact No.:</td>
                                <td><?= $teacher['t_ContactNo'] ?></td>
                            </tr>
                            <tr>
                                <td>Alt-Contact No.:</td>
                                <td><?= $teacher['t_AltContactNo'] ?></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td><?= $teacher['t_Address'] ?></td>
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
