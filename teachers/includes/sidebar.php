<?php

$teacher_login_user = $_SESSION['teacher_login_id'];

// compute current request path and area prefix for building links
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptSegments = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$firstSeg = isset($scriptSegments[0]) ? $scriptSegments[0] : '';
$knownAreas = ['students', 'teachers', 'admin'];
if (in_array($firstSeg, $knownAreas)) {
  $appRoot = '';
} else {
  $appRoot = $firstSeg ? '/' . $firstSeg : '';
}

if (strpos($currentPath, '/teachers/') !== false || $currentPath === '/teachers' || $currentPath === '/teachers/') {
  $areaPrefix = $appRoot . '/teachers';
} else {
  $areaPrefix = $appRoot;
}

function buildTeacherLink($path)
{
  global $areaPrefix;
  return rtrim($areaPrefix, '/') . $path;
}

function isTeacherActive($paths)
{
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $paths = (array)$paths;
  foreach ($paths as $p) {
    if ($uri === $p || strpos($uri, $p) === 0) return true;
    global $areaPrefix;
    $full = rtrim($areaPrefix, '/') . $p;
    if ($uri === $full || strpos($uri, $full) === 0) return true;
  }
  return false;
}

$teacher_result = mysqli_query($conn, "SELECT * FROM `teacher_db` WHERE `t_ID` = '" . mysqli_real_escape_string($conn, $teacher_login_user) . "'");
$teacher_login_user_data = mysqli_fetch_assoc($teacher_result);

?>

<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">SMS</span>
  </div>

  <ul class="nav-links">

    <li>
      <a href="<?= buildTeacherLink('/dashboard.php') ?>" class="<?= isTeacherActive('/dashboard.php') ? 'active' : '' ?>">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="<?= buildTeacherLink('/dashboard.php') ?>">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildTeacherLink('/pages/profile.php') ?>" class="<?= isTeacherActive(['/pages/profile.php', '/pages/edit_profile.php']) ? 'active' : '' ?>">
          <i class='bx bxs-user-rectangle'></i>
          <span class="link_name">Profile</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= buildTeacherLink('/pages/profile.php') ?>">Profile</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildTeacherLink('/pages/viewCourse.php') ?>" class="<?= isTeacherActive(['/pages/viewCourse.php', '/pages/courseSchedule.php']) ? 'active' : '' ?>">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Course</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu <?= isTeacherActive(['/pages/viewCourse.php', '/pages/courseSchedule.php']) ? 'show' : '' ?>">
        <li><a class="link_name" href="<?= buildTeacherLink('/pages/viewCourse.php') ?>">Course</a></li>
        <li><a href="<?= buildTeacherLink('/pages/viewCourse.php') ?>">View Course</a></li>
        <li><a href="<?= buildTeacherLink('/pages/courseSchedule.php') ?>">Course Schedule</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildTeacherLink('/pages/attendance.php') ?>" class="<?= isTeacherActive('/pages/attendance.php') ? 'active' : '' ?>">
          <i class='bx bx-check-square'></i>
          <span class="link_name">Attendance</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= buildTeacherLink('/pages/attendance.php') ?>">Attendance</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildTeacherLink('/pages/addNotice.php') ?>" class="<?= isTeacherActive(['/pages/addNotice.php', '/pages/manageNotice.php']) ? 'active' : '' ?>">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Notice</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu <?= isTeacherActive(['/pages/addNotice.php', '/pages/manageNotice.php']) ? 'show' : '' ?>">
        <li><a class="link_name" href="<?= buildTeacherLink('/pages/addNotice.php') ?>">Notice</a></li>
        <li><a href="<?= buildTeacherLink('/pages/addNotice.php') ?>">Add Notice</a></li>
        <li><a href="<?= buildTeacherLink('/pages/manageNotice.php') ?>">Manage Notice</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildTeacherLink('/pages/grading.php') ?>" class="<?= isTeacherActive('/pages/grading.php') ? 'active' : '' ?>">
          <i class='bx bx-edit-alt'></i>
          <span class="link_name">Grading</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu <?= isTeacherActive('/pages/grading.php') ? 'show' : '' ?>">
        <li><a class="link_name" href="<?= buildTeacherLink('/pages/grading.php') ?>">Grading</a></li>
        <li><a href="<?= buildTeacherLink('/pages/grading.php') ?>">Upload Grading</a></li>
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
        <a href="<?= $appRoot . '/logout.php' ?>"><i class='bx bx-log-out' style="padding-right: 20px;"></i></a>
      </div>
    </li>

  </ul>
</div>