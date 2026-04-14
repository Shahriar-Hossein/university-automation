<?php
require_once BASE_PATH . '/dbconnection.php';

// Compute a dynamic base URL so links work on any host/port/path
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
$base_path = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
$base_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . ($base_path === '' || $base_path === '/' ? '' : $base_path);

if (!isset($_SESSION['admin_login_id'])) {
  header('Location: ' . $base_url . '/adminlogin.php');
  exit;
}
$admin_login_user = $_SESSION['admin_login_id'];
?>

<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">SMS</span>
  </div>

  <ul class="nav-links">

    <li>
      <a href="<?php echo $base_url; ?>/admin/dashboard.php">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="<?php echo $base_url; ?>/admin/dashboard.php">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a>
          <i class='bx bx-collection'></i>
          <span class="link_name">Course</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name">Course</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/addCourse.php">Add Course</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/manageCourse.php">Manage Course</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/manageSection.php">Manage Section</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a>
          <i> 
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 19V4h-4V3H5v16H3v2h12V6h2v15h4v-2zm-6 0H7V5h6zm-3-8h2v2h-2z"/></svg>
          </i>
          <span class="link_name">Rooms</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
        <ul class="sub-menu">
          <li><a class="link_name">Rooms</a></li>
          <li><a href="<?php echo $base_url; ?>/admin/pages/addRoom.php">Add Room</a></li>
          <li><a href="<?php echo $base_url; ?>/admin/pages/manageRooms.php">Manage Room</a></li>
        </ul>
      </li>

    <li>
      <div class="iocn-link">
        <a>
          <i class='bx bxs-time'></i>
          <span class="link_name">Schedule</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name">Schedule</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/teacherSchedule.php">Teacher Schedule</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/courseSchedule.php">Course Schedule</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="">
          <i class='bx bxs-user-rectangle'></i>
          <span class="link_name">Teachers</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Teachers</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/addTeachers.php">Add Teachers</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/manageTeachers.php">Manage Teachers</a></li>

      </ul>
    </li>


    <li>
      <div class="iocn-link">
        <a href="">
          <i class='bx bx-user'></i>
          <span class="link_name">Students</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Students</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/addStudents.php">Add Students</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/manageStudents.php">Manage Students</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/attendance.php">Attendance</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/paymentHistory.php">Payment History</a></li>
      </ul>
    </li>


    <li>
      <div class="iocn-link">
        <a href="">
          <i class='bx bxs-bell'></i>
          <span class="link_name">Public Notice</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Publice Notice</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/addPublicNotice.php">Add Public Notice</a></li>
        <li><a href="<?php echo $base_url; ?>/admin/pages/managePublicNotice.php">Manage Public Notice</a></li>
      </ul>
    </li>

    <li>
      <a href="<?php echo $base_url; ?>/admin/pages/search.php">
        <i class='bx bx-search-alt'></i>
        <span class="link_name">Search</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="<?php echo $base_url; ?>/admin/pages/search.php">Search</a></li>
      </ul>
    </li>

    <li>
      <div class="profile-details">
        <div class="profile-content">
          <img src="../images/profile.jpg" alt="profileImg">
        </div>
        <div class="name-job">
          <div class="profile_name">
            <?php $adminName = mysqli_query($conn, "SELECT * FROM `admin_db` WHERE `Id` = '$admin_login_user'");
            $admin_login_user_data = mysqli_fetch_assoc($adminName);
            echo $admin_login_user_data['adminName'];
            ?>
          </div>
          <div class="job">Admin</div>
        </div>
        <a href="<?php echo $base_url; ?>/logout.php"><i class='bx bx-log-out'></i></a>
      </div>
    </li>

  </ul>
</div>