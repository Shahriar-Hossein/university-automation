<?php
session_start();
require_once('../includes/dbconnection.php');

if (!isset($_SESSION['student_login_id'])) {
  header('Location: ../studentlogin.php');
  exit();
}
$student_login_user = $_SESSION['student_login_id'];

// Query the sections table to get the course IDs
$sections_sql = "
    SELECT 
        sections.*, 
        course_db.*,
        teacher_db.*
    FROM 
        sections 
    JOIN 
        course_db
    ON 
        sections.course_id = course_db.c_ID
    JOIN
        teacher_db
    ON
        sections.teacher_id = teacher_db.t_ID
";

$sections_result = mysqli_query($conn, $sections_sql);

$section_details = [];
if ($sections_result->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($sections_result)) {
    $section_details[] = $row;
  }
}

if (isset($_POST['enroll'])) {
  $course_id = $_POST['course_id'];
  $section_id = $_POST['section_id'];
  $student_id = $_POST['student_id'];

  // Check if the student is already enrolled in the course section
  $check_sql = "SELECT * FROM student_course WHERE course_id = $course_id AND student_id = $student_id";

  $result = mysqli_query($conn, $check_sql);

  if ($result->num_rows > 0) {
    echo "<script>alert('You are already enrolled in this course.')</script>";
    echo "<script>window.open('addCourse.php','_self')</script>";
  } else {
    // Retrieve the new course's schedule
    $new_schedule_sql = "SELECT * FROM sections WHERE id = $section_id";
    $new_schedule_result = mysqli_query($conn, $new_schedule_sql);
    $new_section = mysqli_fetch_assoc($new_schedule_result);
    $new_days = explode(',', $new_section['days']);
    $new_time = $new_section['time'];

    // Retrieve the student's current course schedule
    $current_schedule_sql = "
            SELECT sections.days, sections.time 
            FROM sections 
            INNER JOIN student_course 
            ON sections.id = student_course.section_id 
            WHERE student_course.student_id = $student_id";
    $current_schedule_result = mysqli_query($conn, $current_schedule_sql);

    $has_conflict = false;
    while ($current_section = mysqli_fetch_assoc($current_schedule_result)) {
      $current_days = explode(',', $current_section['days']);
      $current_time = $current_section['time'];

      // Check if there is any overlap in days
      foreach ($new_days as $new_day) {
        if (in_array($new_day, $current_days)) {
          // Check if the time also overlaps
          if ($new_time == $current_time) {
            $has_conflict = true;
            break 2; // Exit both loops
          }
        }
      }
    }

    if ($has_conflict) {
      echo "<script>alert('Time conflict with another course. Please choose another section.')</script>";
      echo "<script>window.open('addCourse.php','_self')</script>";
  } else {
      // Insert the enrollment data
      $enroll_sql = "INSERT INTO student_course (course_id, section_id, student_id) VALUES ($course_id, $section_id, $student_id)";
      if (mysqli_query($conn, $enroll_sql)) {
          
          $course_fee = $_POST['course_fee'];
  
          // Check if a payment record for the student already exists
          $payment_check_sql = "SELECT * FROM payments WHERE student_id = $student_id";
          $payment_check_result = mysqli_query($conn, $payment_check_sql);
  
          if (mysqli_num_rows($payment_check_result) > 0) {
              // If a record exists, update the total_amount
              $payment_update_sql = "UPDATE payments SET total_amount = total_amount + $course_fee WHERE student_id = $student_id";
              if (mysqli_query($conn, $payment_update_sql)) {
                  echo "<script>alert('New course added and payment updated.')</script>";
              } else {
                  echo "<script>alert('Error updating payment.')</script>";
              }
          } else {
              // If no record exists, insert a new payment record
              $payment_insert_sql = "INSERT INTO payments (student_id, total_amount, paid_amount) VALUES ($student_id, $course_fee, 0)";
              if (mysqli_query($conn, $payment_insert_sql)) {
                  echo "<script>alert('New course added and payment record created.')</script>";
              } else {
                  echo "<script>alert('Error creating payment record.')</script>";
              }
          }
  
          echo "<script>window.open('addCourse.php','_self')</script>";
      } else {
          echo "<script>alert('Sorry! Unable to add course.')</script>";
      }
  }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Course || Students</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once('../includes/sidebar.php'); ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Add Course || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
      </div>
      <div class="main_workPanel">
        <div>
          <h3>Add Course</h3>
        </div>
        <div class="admin_monitor">
          <div class="manage_admin_part" style="position: relative;">
            <h2>All Courses</h2>
            <div class="table-group">
              <table style="margin-top: 20px; width: 1500px;">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Section Name</th>
                    <th>Room No.</th>
                    <th>Teacher Name</th>
                    <th>Days</th>
                    <th>Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  foreach ($section_details as $index => $section):
                    // Check if the student is already enrolled in this course section
                    $check_enrollment_sql = "SELECT * FROM student_course WHERE course_id = {$section['course_id']} AND student_id = $student_login_user";
                    $enrollment_result = mysqli_query($conn, $check_enrollment_sql);
                    $is_enrolled = $enrollment_result->num_rows > 0;

                    // Check if the course is completed
                    $completed_sql = "SELECT * FROM student_course WHERE course_id = {$section['course_id']} AND student_id = $student_login_user AND status = 'completed'";
                    $completed_result = mysqli_query($conn, $completed_sql);
                    $is_completed = $completed_result->num_rows > 0;
                  ?>
                    <tr>
                      <td><?= $index + 1 ?> </td>
                      <td><?= $section['c_title'] ?></td>
                      <td><?= $section['c_code'] ?></td>
                      <td><?= $section['section'] ?></td>
                      <td><?= $section['room'] ?></td>
                      <td><?= $section['t_Name'] ?></td>
                      <td><?= $section['days'] ?></td>
                      <td><?= $section['time'] ?></td>
                      <td>
                        <form action="" method="POST">
                          <input type="hidden" name="course_id" value="<?= $section['course_id'] ?>">
                          <input type="hidden" name="section_id" value="<?= $section['id'] ?>">
                          <input type="hidden" name="student_id" value="<?= $student_login_user ?>">
                          <input type="hidden" name="course_fee" value="<?= $section['c_hours'] * 3000 ?>">
                          <?php if ($is_completed): ?>
                            <button type="button" style="width: 120px; height: 40px; border-radius: 10px; background: lightgray; color: black; font-size: medium; font-weight: 700; cursor: not-allowed;" disabled>Completed</button>
                          <?php elseif ($is_enrolled): ?>
                            <button type="button" style="width: 120px; height: 40px; border-radius: 10px; background: lightblue; color: black; font-size: medium; font-weight: 700; cursor: not-allowed;" disabled>Enrolled</button>
                          <?php else: ?>
                            <button type="submit" style="width: 120px; height: 40px; border-radius: 10px; background: whitesmoke; color: rgb(0, 0, 0); font-size: medium; font-weight: 700; cursor: pointer;" name="enroll">Enroll</button>
                          <?php endif; ?>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>

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