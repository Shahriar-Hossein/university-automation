<?php

$student_login_user = $_SESSION['student_login_id'];

$studentName = mysqli_query($conn, "SELECT * FROM `student_db` WHERE `s_ID` = '$student_login_user'");
$student_login_user_data = mysqli_fetch_assoc($studentName);
if ( $student_login_user_data != null ){
  $student_photo = $student_login_user_data['s_Photo'];
  echo "
    <script> 
      console.log($student_photo);
      console.log('from sidebar');
    </script>
  ";
}

?>

<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">SMS</span>
  </div>

  <ul class="nav-links">

    <li>
      <a href="http://localhost/sms/students/dashboard.php">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="http://localhost/sms/students/dashboard.php">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="http://localhost/sms/students/pages/profile.php">
          <i class='bx bxs-user-rectangle'></i>
          <span class="link_name">Profile</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="http://localhost/sms/students/pages/profile.php">Profile</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="http://localhost/sms/students/pages/viewCourse.php">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Course</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="http://localhost/sms/students/pages/viewCourse.php">Course</a></li>
          <li><a href="http://localhost/sms/students/pages/viewCourse.php">View Course</a></li>
          <li><a href="http://localhost/sms/students/pages/courseSchedule.php">Course Schedule</a></li>
          <li><a href="http://localhost/sms/students/pages/addCourse.php">Add Course</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="http://localhost/sms/students/pages/payment.php">
          <i class='bx bx-check-square'></i>
          <span class="link_name">Payment</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="http://localhost/sms/students/pages/payment.php">Payment</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="http://localhost/sms/students/pages/grades.php">
        <i class='bx bx-edit-alt'></i>
          <span class="link_name">Grades</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="http://localhost/sms/students/pages/grades.php">Grades</a></li>
      </ul>
    </li>


    <li>
      <div class="profile-details">
        <div class="profile-content">
          <img src='<?= $student_login_user_data['s_Photo'] ?>' alt="profileImg">
        </div>
        <div class="name-job">
          <div class="profile_name">
            <?= $student_login_user_data['s_Name'] ?>
          </div>
          <div class="job">Student</div>
        </div>
        <a href="http://localhost/sms/logout.php"><i class='bx bx-log-out' style="padding-right: 20px;"></i></a>        
      </div>
    </li>

  </ul>
</div>