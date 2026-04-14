<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

// Check if the student is logged in
if (!isset($_SESSION['student_login_id'])) {
    header('Location: ../studentlogin.php');
    exit();
}

$student_login_id = $_SESSION['student_login_id'];

// Fetch grades for the logged-in student
$grades_sql = "
    SELECT 
        course_db.c_title, 
        grades.grade, 
        grades.status 
    FROM 
        grades 
    JOIN 
        course_db 
    ON 
        grades.course_id = course_db.c_ID 
    WHERE 
        grades.student_id = $student_login_id
";
$grades_result = mysqli_query($conn, $grades_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades || Students</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php include_once '../includes/sidebar.php'; ?>

    <section class="home-section">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">My Grades || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
            </div>
            <div class="main_workPanel">
                <div class="admin_monitor">
                    <div class="manage_admin_part" style="position: relative;">
                        <h2>My Grades</h2>
                        <div class="table-group">
                            <table style="margin-top: 20px; width: 1500px;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Course Name</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($grades_result) > 0): ?>
                                        <?php $index = 1; ?>
                                        <?php while ($grade = mysqli_fetch_assoc($grades_result)): ?>
                                            <tr>
                                                <td><?= $index ?></td>
                                                <td><?= $grade['c_title'] ?></td>
                                                <td><?= $grade['grade'] ?></td>
                                                <!-- <td><?= $grade['status'] ?></td> -->
                                                <td><?= 'Completed' ?></td>
                                                </tr>
                                            <?php $index++; ?>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">No grades available.</td>
                                        </tr>
                                    <?php endif; ?>
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
