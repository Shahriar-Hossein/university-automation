<?php
session_start();
require_once('../../dbconnection.php');
if(!isset($_SESSION['teacher_login_id'])){
    header('Location: ../../teacherlogin.php');
}

// Query the sections table to get the course IDs
$sections_sql = "SELECT course_id FROM sections where teacher_id = ". $_SESSION['teacher_login_id'];
$sections_result = mysqli_query($conn, $sections_sql);

$course_ids = [];
if ($sections_result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($sections_result)) {
        $course_ids[] = $row['course_id'];
    }
}

$courses = [];
// Using the course IDs to query the course_db table
if (!empty($course_ids)) {
    $course_ids_string = implode(",", array_map('intval', $course_ids));
    $courses_sql = "SELECT * FROM course_db WHERE c_ID IN ($course_ids_string)";
    $courses_result = mysqli_query($conn, $courses_sql);


    if ($courses_result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($courses_result)) {
            $courses[] =  $row;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course ||Teacher</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
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
                <span class="text">View Course || <span style="font-weight: 300; margin-left: 10px;">Teacher</span></span>
            </div>
            <div class="main_workPanel">
                <div>
                    <h3>View Course</h3>
                </div>
                <div class="admin_monitor">
                    <div class="manage_admin_part" style="position: relative;">
                        <h2>View Course</h2>
                        <div style="position: absolute; top:20px; left:750px;"><a href="<?= BASE_URL ?>teachers/pages/courseSchedule.php"><i class='bx bx-timer' style="font-size: 30px;;"></i></a></div>
                        <div class="table-group">
                            <table style="margin-top: 20px; width: 800px;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($courses as $index => $course){
                                            echo '
                                                <tr>
                                                    <td>' . ($index + 1) . '</td>
                                                    <td>' . $course['c_title'] . '</td>
                                                    <td>' . $course['c_code'] . '</td>
                                                </tr>
                                            ';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>