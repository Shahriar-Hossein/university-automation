<?php
session_start();
require_once('includes/dbconnection.php');
if (!isset($_SESSION['student_login_id'])) {
  header('Location: ../studentlogin.php');
}
$student_login_user = $_SESSION['student_login_id'];
$total_courses_sql = "
    SELECT COUNT(*) AS total_courses
    FROM student_course
    WHERE student_id = '$student_login_user' 
    AND status = 'in-progress'
";
$total_courses_result = mysqli_query($conn, $total_courses_sql);
$total_courses_data = mysqli_fetch_assoc($total_courses_result);
$total_courses = $total_courses_data['total_courses'] ?? 0; 


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard||Students</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="website icon" type="png" href="./images/weblogo.png">
  <!-- Boxiocns CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once('includes/sidebar.php'); ?>


  <section class="home-section" style="height: 1800px">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Welcome Student Dashboard</span>
      </div>
      <div class="dashboard_body">
        <h2 class="dashboard-title">Report Summary</h2>
        <div class="card-container">

          <div class="card">
            <div class="card-icon class">
              <i class='bx bx-collection'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Course</h3>
              <p class="card-number"><?php echo $total_courses; ?></p>
              <a href="http://localhost/sms/students/pages/viewCourse.php" class="card-link">View Course</a>
            </div>
          </div>

          <!-- <div class="card">
            <div class="card-icon schedule">
              <i class='bx bx-time' style="font-size: 50px;"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Schedule</h3>
              <p class="card-number">10</p>
              <a href="#" class="card-link">View Schedule</a>
            </div>
          </div> -->

          <div class="card">
            <div class="card-icon teachers">
              <i class='bx bxs-user-rectangle' style="color: white;"></i>
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
              <a href="#" class="card-link">View Teachers</a>
            </div>
          </div>


          <div class="card">
            <div class="card-icon notice">
              <i class='bx bx-message-alt-minus'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Class Notice</h3>
              <p class="card-number">5</p>
              <a href="#" class="card-link">View Notices</a>
            </div>
          </div>
          <div class="card">
            <div class="card-icon pnotice">
              <i class='bx bxs-bell'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Public Notice</h3>
              <?php
                $dash_pn_query = "SELECT * from public_notice_db";
                $dash_pn_query_result = mysqli_query($conn, $dash_pn_query);
                if ($pn_total = mysqli_num_rows($dash_pn_query_result)) {
                  echo '<p class="card-number">' . $pn_total . '</p>';
                } else {
                  echo '<p class="card-number">No Data</p>';
                }
                ?>
              <a href="#" class="card-link">View Public Notices</a>
            </div>
          </div>
        </div>
        <div class="update-report">
          <a href=""><i class='bx bx-refresh'></i> Updated Report</a>
        </div>
      </div>


      <?php include_once('includes/notice.php'); ?>


    </div>
  </section>


  <script src="js/script.js"></script>
</body>

</html>