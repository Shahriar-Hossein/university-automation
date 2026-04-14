<?php

$student_login_user = $_SESSION['student_login_id'];

// compute current request path and area prefix for building links
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Default app root (attempt to infer from SCRIPT_NAME)
$scriptSegments = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$firstSeg = isset($scriptSegments[0]) ? $scriptSegments[0] : '';
$knownAreas = ['students', 'teachers', 'admin'];
// If the first segment is one of the area folders, the app root is empty (served from project root).
if (in_array($firstSeg, $knownAreas)) {
  $appRoot = '';
} else {
  $appRoot = $firstSeg ? '/' . $firstSeg : '';
}

// Determine which area we're in and build prefix without duplicating segments
if (strpos($currentPath, '/students/') !== false || $currentPath === '/students' || $currentPath === '/students/') {
  $areaPrefix = $appRoot . '/students';
} elseif (strpos($currentPath, '/teachers/') !== false || $currentPath === '/teachers' || $currentPath === '/teachers/') {
  $areaPrefix = $appRoot . '/teachers';
} elseif (strpos($currentPath, '/admin/') !== false || $currentPath === '/admin' || $currentPath === '/admin/') {
  $areaPrefix = $appRoot . '/admin';
} else {
  // fallback to app root
  $areaPrefix = $appRoot;
}

function buildLink($path)
{
  global $areaPrefix;
  return rtrim($areaPrefix, '/') . $path;
}

function isActive($paths)
{
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $paths = (array)$paths;
  foreach ($paths as $p) {
    // check raw path and path with area prefix
    if ($uri === $p || strpos($uri, $p) === 0) return true;
    global $areaPrefix;
    $full = rtrim($areaPrefix, '/') . $p;
    if ($uri === $full || strpos($uri, $full) === 0) return true;
  }
  return false;
}

$studentName = mysqli_query($conn, "SELECT * FROM `student_db` WHERE `s_ID` = '$student_login_user'");
$student_login_user_data = mysqli_fetch_assoc($studentName);
if ($student_login_user_data != null) {
  $student_photo = $student_login_user_data['s_Photo'];
  echo "<script>console.log('" . addslashes($student_photo) . "');console.log('from sidebar');</script>";
}

?>

<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">SMS</span>
  </div>

  <ul class="nav-links">

    <li>
      <a href="<?= buildLink('/dashboard.php') ?>" class="<?= isActive('/dashboard.php') ? 'active' : '' ?>">
        <i class='bx bx-grid-alt'></i>
        <span class="link_name">Dashboard</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="<?= buildLink('/dashboard.php') ?>">Dashboard</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildLink('/pages/profile.php') ?>" class="<?= isActive('/pages/profile.php') ? 'active' : '' ?>">
          <i class='bx bxs-user-rectangle'></i>
          <span class="link_name">Profile</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= buildLink('/pages/profile.php') ?>">Profile</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildLink('/pages/viewCourse.php') ?>" class="<?= isActive(['/pages/viewCourse.php','/pages/courseSchedule.php','/pages/addCourse.php']) ? 'active' : '' ?>">
          <i class='bx bx-message-alt-minus'></i>
          <span class="link_name">Course</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu <?= isActive(['/pages/viewCourse.php','/pages/courseSchedule.php','/pages/addCourse.php']) ? 'show' : '' ?>">
        <li><a class="link_name" href="<?= buildLink('/pages/viewCourse.php') ?>">Course</a></li>
          <li><a href="<?= buildLink('/pages/viewCourse.php') ?>">View Course</a></li>
          <li><a href="<?= buildLink('/pages/courseSchedule.php') ?>">Course Schedule</a></li>
          <li><a href="<?= buildLink('/pages/addCourse.php') ?>">Add Course</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildLink('/pages/payment.php') ?>" class="<?= isActive('/pages/payment.php') ? 'active' : '' ?>">
          <i class='bx bx-check-square'></i>
          <span class="link_name">Payment</span>
        </a>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="<?= buildLink('/pages/payment.php') ?>">Payment</a></li>
      </ul>
    </li>

    <li>
      <div class="iocn-link">
        <a href="<?= buildLink('/pages/grades.php') ?>" class="<?= isActive('/pages/grades.php') ? 'active' : '' ?>">
        <i class='bx bx-edit-alt'></i>
          <span class="link_name">Grades</span>
        </a>
        <i class='bx bxs-chevron-down arrow'></i>
      </div>
      <ul class="sub-menu <?= isActive('/pages/grades.php') ? 'show' : '' ?>">
        <li><a class="link_name" href="<?= buildLink('/pages/grades.php') ?>">Grades</a></li>
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
        <a href="<?= $appRoot . '/logout.php' ?>"><i class='bx bx-log-out' style="padding-right: 20px;"></i></a>        
      </div>
    </li>

  </ul>
</div>