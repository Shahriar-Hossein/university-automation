<?php

$teacher_login_user = $_SESSION['teacher_login_id'];

$teacher_result = mysqli_query($conn, "SELECT * FROM `teacher_db` WHERE `t_ID` = '$teacher_login_user'");
$teacher_login_user_data = mysqli_fetch_assoc($teacher_result);

?>

<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">SMS</span>
  </div>

  <ul class="nav-links">

    <li>
      <a href="<?= BASE_URL ?>teachers/dashboard.php">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="<?= BASE_URL ?>teachers/dashboard.php">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= BASE_URL ?>teachers/pages/profile.php">
          <i class='bx bxs-user-rectangle'></i>
          <span class="link_name">Profile</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= BASE_URL ?>teachers/pages/profile.php">Profile</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= BASE_URL ?>teachers/pages/viewCourse.php">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Course</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= BASE_URL ?>teachers/pages/viewCourse.php">Course</a></li>
        <li><a href="<?= BASE_URL ?>teachers/pages/viewCourse.php">View Course</a></li>
        <li><a href="<?= BASE_URL ?>teachers/pages/courseSchedule.php">Course Schedule</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= BASE_URL ?>teachers/pages/attendance.php">
          <i class='bx bx-check-square'></i>
          <span class="link_name">Attendance</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= BASE_URL ?>teachers/pages/attendance.php">Attendance</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Notice</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Notice</a></li>
        <li><a href="<?= BASE_URL ?>teachers/pages/addNotice.php">Add Notice</a></li>
        <li><a href="<?= BASE_URL ?>teachers/pages/manageNotice.php">Manage Notice</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="">
        <i class='bx bx-edit-alt'></i>
          <span class="link_name">Grading</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Grading</a></li>
        <li><a href="<?= BASE_URL ?>teachers/pages/grading.php">Upload Grading</a></li>
      </ul>
    </li>

    <li>
      <div class="profile-details">
        <div class="profile-content">
          <img src='<?= $teacher_login_user_data['t_ProfilePic'] ?>' alt="profileImg">
        </div>
        <div class="name-job">
          <div class="profile_name"><?= $teacher_login_user_data['t_Name'] ?></div>
          <div class="job">Teacher</div>
        </div>
        <a href="<?= BASE_URL ?>logout.php"><i class='bx bx-log-out' style="padding-right: 20px;"></i></a>        
      </div>
    </li>


    

  </ul>
</div>