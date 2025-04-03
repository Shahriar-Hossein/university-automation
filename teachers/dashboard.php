<?php
session_start();
require_once('../dbconnection.php');

if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../teacherlogin.php');
    exit();
}

// Fetch the teacher's login ID from the session
$teacher_login_user = $_SESSION['teacher_login_id'];

// Fetch teacher information (optional, based on your usage)
$teacher_result = mysqli_query($conn, "SELECT * FROM teacher_db WHERE t_ID = '$teacher_login_user'");
$teacher_login_user_data = mysqli_fetch_assoc($teacher_result);

// Query to get the course IDs assigned to the teacher
$sections_sql = "SELECT course_id FROM sections WHERE teacher_id = '$teacher_login_user'";
$sections_result = mysqli_query($conn, $sections_sql);

$course_ids = [];
if ($sections_result && mysqli_num_rows($sections_result) > 0) {
    while ($row = mysqli_fetch_assoc($sections_result)) {
        $course_ids[] = $row['course_id'];
    }
}

// Count the total number of courses assigned
$total_courses = count($course_ids);


$total_notices_sql = "
  SELECT COUNT(*) AS total_notices
  FROM notices 
  JOIN sections ON notices.section_id = sections.id
  WHERE sections.teacher_id = '$teacher_login_user'
";
$total_notices_result = mysqli_query($conn, $total_notices_sql);
$total_notices_data = mysqli_fetch_assoc($total_notices_result);
$total_notices = $total_notices_data['total_notices'] ?? 0;

?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard||Teacher</title>
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
        <span class="text">Welcome Teacher Dashboard</span>
      </div>
      <div class="dashboard_body">
        <h2 class="dashboard-title">Report Summary of <?= $teacher_login_user_data['t_Name'] ?></h2>
        <div class="card-container">


          <!-- <div class="card">
            <div class="card-icon teachers">
              <i class='bx bxs-user-rectangle'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Course Schedule</h3>
              <p class="card-number">10</p>
              <a href="#" class="card-link">View Schedule</a>
            </div>
          </div> -->

          <div class="card">
            <div class="card-icon class">
              <i class='bx bx-collection'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Total Courses</h3>
              <p class="card-number"><?php echo $total_courses; ?></p>
              <a href="viewCourses.php" class="card-link">View Courses</a>
            </div>
          </div>


          <div class="card">
            <div class="card-icon students">
              <i class='bx bx-user'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Total Students</h3>
              <p class="card-number">5</p>
              <a href="#" class="card-link">View Students</a>
            </div>
          </div>
          <div class="card">
            <div class="card-icon notice">
              <i class='bx bx-message-alt-minus'></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">Total Class Notice</h3>
              <p class="card-number"><?php echo $total_notices; ?></p>
              <a href="#" class="card-link">View Notices</a>
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
              <a href="#pb_notice" class="card-link">View Public Notices</a>
            </div>
          </div>
          <?php include_once('../calender/calender.php') ?>
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