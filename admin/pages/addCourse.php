<?php
session_start();
require_once '../includes/dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: ../adminlogin.php');
}
$admin_login_user = $_SESSION['admin_login_id'];

if (isset($_POST['addcourseBTN'])) {
    $c_Title = $_POST['course_title'];
    $c_Code = $_POST['course_code'];
    $c_Hour = $_POST['course_hour'];

    // $c_Section = isset($_POST['sections']) ? implode(',', $_POST['sections']) : '';


    $course_sql = "insert into course_db(c_title, c_code, c_hours) values('$c_Title', '$c_Code', '$c_Hour')";

    if (mysqli_query($conn, $course_sql)) {
        echo "<script>alert('New course data inserted')</script>";
        echo "<script>window.open('addCourse.php','_self')</script>";
    } else {
        echo "<script>alert('Sorry! data has not inserted')</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class ||Admin</title>
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once('../includes/sidebar.php'); ?>
    <section class="home-section">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">Add Course || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
            </div>
            <div class="main_workPanel">
                <div class="main_workPanel_header">
                    <h3>Add Course</h3>
                </div>
                <div class="admin_monitor_add" style="margin: 0 auto;">
                    <form class="form" action="" method="POST">
                        <h2>Add Course</h2>
                        <div class="form-group">
                            <label for="course-title">Course Title</label>
                            <input type="text" id="course_title" name="course_title" required style="margin: 0 auto;">
                        </div>
                        <div class="form-group">
                            <label for="course-code">Course Code</label>
                            <input type="text" id="course_code" name="course_code" required>
                        </div>
                        <div class="form-group">
                            <label for="section">Credit Hours</label>
                            <select id="course_hour" name="course_hour" required>
                                <option value="" disabled selected>Choose Credit Hours</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>
                        </div>

                        <!-- <div class="form-group">
                            <label for="section">Sections</label>
                            <div class="form-group checkbox-group">
                                <div class="checkbox_align">
                                    <label><input type="checkbox" name="sections[]" value="A">A</label>
                                </div>
                                <div class="checkbox_align">
                                    <label><input type="checkbox" name="sections[]" value="B">B</label>
                                </div>
                                <div class="checkbox_align">
                                    <label><input type="checkbox" name="sections[]" value="C">C</label>
                                </div>
                                <div class="checkbox_align">
                                    <label><input type="checkbox" name="sections[]" value="D">D</label>
                                </div>
                            </div>
                        </div> -->
                        <button style="text-align: center; width: 400px" type="submit" class="add-button" name="addcourseBTN">Add Course</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>