<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';
if (!isset($_SESSION['student_login_id'])) {
  header('Location: ../studentlogin.php');
}
$student_login_user = $_SESSION['student_login_id'];

$dbug = mysqli_query($conn, 'SELECT * FROM student_course WHERE student_id = ' . $student_login_user);
echo '
<script>
  console.log(' . $dbug->num_rows .');
</script>
';
// Query the sections table to get the course IDs
$sections_sql = "
        SELECT 
            student_course.*,
            sections.*, 
            course_db.*
        FROM 
            student_course 
        JOIN 
            course_db 
        ON 
            student_course.course_id = course_db.c_ID 
        JOIN 
            sections
        ON 
            student_course.section_id = sections.id
        WHERE 
            student_course.student_id = " . $_SESSION['student_login_id'] ."
        AND student_course.status = 'in-progress'";
$sections_result = mysqli_query($conn, $sections_sql);

$section_details = [];
if ($sections_result->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($sections_result)) {
    $section_details[] = $row;
  }
}
echo '
<script>
  console.log(' . $sections_result->num_rows .');
</script>
';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Course ||Students</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once '../includes/sidebar.php'; ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">View Course || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
      </div>
      <div class="main_workPanel">
        <!-- <div>
          <h3>View Course</h3>
        </div> -->
        <div class="admin_monitor">
          <div class="manage_admin_part" style="position: relative;">
            <h2>View Course</h2>
            <div style="position: absolute; top:20px; right:280px;"><a href="http://localhost/sms/students/pages/courseSchedule.php"><i class='bx bx-timer' style="font-size: 30px;;"></i></a></div>
            <div class="table-group">
              <table style="margin-top: 20px; width: 1500px;">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Section Name</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($section_details as $index => $section) {
                    echo '
                      <tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . $section['c_title'] . '</td>
                        <td>' . $section['c_code'] . '</td>
                        <td>' . $section['section'] . '</td>
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