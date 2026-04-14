<?php
session_start();

if (!isset($_SESSION['admin_login_id'])) {
  header('Location: ../adminlogin.php');
}

$admin_login_user = $_SESSION['admin_login_id'];

// Ensure DB connection is available for sidebar and queries
include_once('../dbconnection.php');

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="website icon" type="png" href="./images/weblogo.png">
  <!-- Boxiocns CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once('includes/sidebar.php'); ?>

  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Welcome Admin Dashboard</span>
      </div>
      <div class="dashboard_body">


        <h2 class="dashboard-title">Report Summary</h2>
        <div class="bodytwo" style="display: flex; justify-content:space-between; align-items:center;">
          <div class="card-container">

            <div class="card">
              <div class="card-icon class">
                <i class='bx bx-collection'></i>
              </div>
              <div class="card-content">
                <h3 class="card-title">Total Course</h3>
                <?php
                $dash_course_query = "SELECT * from course_db";
                $dash_course_query_result = mysqli_query($conn, $dash_course_query);
                if ($course_total = mysqli_num_rows($dash_course_query_result)) {
                  echo '<p class="card-number">' . $course_total . '</p>';
                } else {
                  echo '<p class="card-number">No Data</p>';
                }
                ?>
                <a href="<?php echo $base_url; ?>/admin/pages/manageCourse.php" class="card-link">View Course</a>
              </div>
            </div>

            <div class="card">
              <div class="card-icon teachers">
                <i class='bx bxs-user-rectangle'></i>
              </div>
              <div class="card-content">
                <h3 class="card-title">Total Teachers</h3>
                <?php
                $dash_teachers_query = "SELECT * from teacher_db";
                $dash_teacher_query_result = mysqli_query($conn, $dash_teachers_query);
                if ($teacher_total = mysqli_num_rows($dash_teacher_query_result)) {
                  echo '<p class="card-number">' . $teacher_total . '</p>';
                } else {
                  echo '<p class="card-number">No Data</p>';
                }
                ?>
                <a href="<?php echo $base_url; ?>/admin/pages/manageTeachers.php" class="card-link">View Teachers</a>
              </div>
            </div>

            <div class="card">
              <div class="card-icon students">
                <i class='bx bx-user'></i>
              </div>
              <div class="card-content">
                <h3 class="card-title">Total Students</h3>
                <?php
                $dash_students_query = "SELECT * from student_db";
                $dash_students_query_result = mysqli_query($conn, $dash_students_query);
                if ($students_total = mysqli_num_rows($dash_students_query_result)) {
                  echo '<p class="card-number">' . $students_total . '</p>';
                } else {
                  echo '<p class="card-number">No Data</p>';
                }
                ?>
                <a href="<?php echo $base_url; ?>/admin/pages/manageStudents.php" class="card-link">View Students</a>
              </div>
            </div>

            <div class="card">
              <div class="card-icon pnotice">
                <i class='bx bxs-bell'></i>
              </div>
              <div class="card-content">
                <h3 class="card-title">Total Public Notice</h3>
                <?php
                $dash_pn_query = "SELECT * from public_notice_db";
                $dash_pn_query_result = mysqli_query($conn, $dash_pn_query);
                if ($pn_total = mysqli_num_rows($dash_pn_query_result)) {
                  echo '<p class="card-number">' . $pn_total . '</p>';
                } else {
                  echo '<p class="card-number">No Data</p>';
                }
                ?>
                <a href="<?php echo $base_url; ?>/admin/pages/managePublicNotice.php" class="card-link">View Public Notices</a>
              </div>
            </div>
          </div>
          <?php include_once('../calender/calender.php') ?>
        </div>

        <div class="update-report">
          <a href="<?php echo $base_url; ?>/admin/dashboard.php"><i class='bx bx-refresh'></i> Updated Report</a>
        </div>
      </div>
    </div>
  </section>


  <script src="js/script.js"></script>
</body>

</html>