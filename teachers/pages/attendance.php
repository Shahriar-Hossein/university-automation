<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['teacher_login_id'])) {
  header('Location: ../teacherlogin.php');
  exit();
}

$teacher_login_user = $_SESSION['teacher_login_id'];

// Fetch courses and sections for the logged-in teacher
$courses_sql = "
    SELECT 
        sections.id as section_id, 
        course_db.c_ID as course_id,
        course_db.c_title, 
        sections.section 
    FROM 
        sections 
    JOIN 
        course_db 
    ON 
        sections.course_id = course_db.c_ID 
    WHERE 
        sections.teacher_id = $teacher_login_user
    ";
$courses_result = mysqli_query($conn, $courses_sql);

$courses = [];
if ($courses_result->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($courses_result)) {
    $courses[] = $row;
  }
}

// Array to store unique courses
$unique_courses = [];

// Array to track unique course IDs
$course_ids = [];

foreach ($courses as $course) {
    // Check if the course_id is already in the $course_ids array
    if (!in_array($course['course_id'], $course_ids)) {
        // If it's not, add the course_id to the tracking array
        $course_ids[] = $course['course_id'];

        // Add the unique course to the $unique_courses array
        $unique_courses[] = $course;
    }
}

// Handle form submission
$selected_course_id = $_POST['course_id'] ?? null;
$selected_section_id = $_POST['section_id'] ?? null;
$selected_date = $_POST['date'] ?? null;
echo '
<script>
console.log(' . $selected_date . ');
</script>
';

$students = [];
$attendance_records = [];

if ($selected_course_id && $selected_section_id && $selected_date) {
  // Check if attendance exists for the selected date, course, and section
  $attendance_check_sql = "
        SELECT 
            attendances.student_id, 
            student_db.s_Name, 
            attendances.status 
        FROM 
            attendances 
        JOIN 
            student_db 
        ON 
            attendances.student_id = student_db.s_ID 
        WHERE 
            attendances.course_id = $selected_course_id 
            AND attendances.section_id = $selected_section_id 
            AND attendances.date = '$selected_date'";

  $attendance_result = mysqli_query($conn, $attendance_check_sql);

  if ($attendance_result->num_rows > 0) {
    // If attendance exists for the selected date, fetch the attendance records
    while ($row = mysqli_fetch_assoc($attendance_result)) {
      $attendance_records[] = $row;
    }
  } else {
    // If no attendance exists, fetch students for the selected course and section
    $students_sql = "
            SELECT 
                student_course.student_id, 
                student_db.s_Name 
            FROM 
                student_course 
            JOIN 
                student_db 
            ON 
                student_course.student_id = student_db.s_ID 
            WHERE 
                student_course.course_id = $selected_course_id 
                AND student_course.section_id = $selected_section_id";
    $students_result = mysqli_query($conn, $students_sql);

    if ($students_result->num_rows > 0) {
      while ($row = mysqli_fetch_assoc($students_result)) {
        $students[] = $row;
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
  <title>Attendance ||Teachers</title>
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../pages/css/modal.css">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
</head>

<body>
  <?php include_once('../includes/sidebar.php'); ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Attendance || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
      </div>
      <div class="main_workPanel">
        <div>
          <h3>Attendance</h3>
        </div>
        <div class="admin_monitor">
          <div class="manage_admin_part">
            <h2>Attendance of Students Overview</h2>
            <form method="POST" action="">
              <div class="form-group" style="display:flex; margin-top: 20px;">
                <div>
                  <label for="course">Course Name</label>
                  <select id="course" name="course_id" required onchange="this.form.submit()">
                    <option value="" disabled selected>Choose Course Name</option>
                    <?php
                    foreach ($unique_courses as $course) {
                      $selected = ($selected_course_id == $course['course_id']) ? 'selected' : '';
                      echo "<option value='{$course['course_id']}' $selected>{$course['c_title']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <div style="margin-left: 25px;">
                  <label for="section">Section</label>
                  <select id="section" name="section_id" required onchange="this.form.submit()">
                    <option value="" disabled selected>Choose Section</option>
                    <?php
                    if ($selected_course_id) {
                      foreach ($courses as $course) {
                        if ($course['course_id'] == $selected_course_id) {
                          $selected = ($selected_section_id == $course['section_id']) ? 'selected' : '';
                          echo "<option value='{$course['section_id']}' $selected>{$course['section']}</option>";
                        }
                      }
                    }
                    ?>
                  </select>
                </div>

                <div style="margin-left: 25px;">
                  <label for="date">Date</label>
                  <input onchange="this.form.submit();" type="date" id="date" name="date" required max="<?php echo date('Y-m-d'); ?>" value="<?php echo $selected_date; ?>">
                </div>

              </div>
            </form>

            <?php if ($attendance_records): ?>
              <form method="POST" action="../backend_requests/saveAttendance.php">
                <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                <input type="hidden" name="section_id" value="<?php echo $selected_section_id; ?>">
                <input type="hidden" name="date" value="<?php echo $selected_date; ?>">
                <!-- Display existing attendance -->
                <div style="margin-top: 10px; width: 60%; margin: 0 auto; background: whitesmoke; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px;" class="table-group">
                  <h1 style="text-align: center; padding: 20px 0;">
                    Edit Attendance
                  </h1>
                  <table>
                    <thead>
                      <tr>
                        <th style="text-align: center;">SI No.</th>
                        <th style="text-align: center;">Student ID</th>
                        <th style="text-align: center;">Student Name</th>
                        <th style="text-align: center;">Present</th>
                        <th style="text-align: center;">Absent</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($attendance_records as $index => $record) : ?>
                        <tr>
                          <td style="text-align: center;"><?= $index + 1 ?> </td>
                          <td style="text-align: center;"><?= $record['student_id'] ?></td>
                          <td style="text-align: center;"><?= $record['s_Name'] ?></td>
                          <td>
                            <div style="display:flex; justify-content: center;">
                              <input name=<?= 'attendance[' . $record['student_id'] . ']' ?> <?= $record['status'] === '1' ? 'checked' : '' ?> value='1' type='radio'>
                            </div>
                          </td>
                          <td>
                            <div style="display:flex; justify-content: center;">
                              <input name=<?= 'attendance[' . $record['student_id'] . ']' ?> <?= $record['status'] === '0' || !$record['status'] ? 'checked' : '' ?> value='0' type='radio'>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                  <button type="submit" class="btn btn-primary"
                    style="background-color: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">
                    Update Attendance
                  </button>
                </div>
              </form>
            <?php elseif ($students): ?>
              <!-- Allow the teacher to take attendance -->
              <form method="POST" action="../backend_requests/saveAttendance.php" id="attendanceForm">
                <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                <input type="hidden" name="section_id" value="<?php echo $selected_section_id; ?>">
                <input type="hidden" name="date" value="<?php echo $selected_date; ?>">
                <div style="margin-top: 10px; width: 60%; margin: 0 auto; background: whitesmoke; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px;" class="table-group">
                  <h1 style="text-align: center; padding: 20px 0;">
                    Create Attendance
                  </h1>
                  <table>
                    <thead>
                      <tr>
                        <th style="text-align: center;">SI No.</th>
                        <th style="text-align: center;">Student ID</th>
                        <th style="text-align: center;">Student Name</th>
                        <th style="text-align: center;">Present</th>
                        <th style="text-align: center;">Absent</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($students as $index => $student) {
                        echo "
                          <tr>
                            <td style='text-align: center;'>" . ($index + 1) . "</td>
                            <td style='text-align: center;'>{$student['student_id']}</td>
                            <td style='text-align: center;'>{$student['s_Name']}</td>
                            <td >
                              <div style='display:flex; justify-content: center;'>
                                <input name='attendance[{$student['student_id']}]' value='1' type='radio'>
                              </div>
                            </td>
                            <td >
                              <div style='display:flex; justify-content: center;'>
                                <input name='attendance[{$student['student_id']}]' value='0' checked type='radio'>
                              </div>
                            </td>
                          </tr>
                        ";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <!-- <div style="margin-top: 20px; text-align: center;">
                  <button type="submit" class="btn btn-primary"
                    style="background-color: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">
                    Submit Attendance
                  </button>
                </div> -->
                <div style="margin-top: 20px; text-align: center;">
                  <button type="button" onclick="openModal();" class="btn btn-primary"
                    style="background-color: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">
                    Update Attendance
                  </button>
                </div>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Confirmation Modal -->
  <div id="modalOverlay" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="text-align: center;">
      <div>
        <h2>Confirm Attendance Submission</h2>
        <p style="margin-top: 5px;">Are you sure you want to submit the attendance?</p>
        <button class="modal-button" onclick="confirmSubmission();">Yes, Submit</button>
        <button class="modal-button" onclick="closeModal();">Cancel</button>
      </div>
    </div>
  </div>

  <script src="../js/script.js"></script>
  <script>
    // Function to open the modal
    function openModal() {
      document.getElementById('modalOverlay').style.display = 'flex';
    }

    // Function to close the modal
    function closeModal() {
      document.getElementById('modalOverlay').style.display = 'none';
    }

    // Function to confirm submission and submit the form
    function confirmSubmission() {
      document.getElementById('attendanceForm').submit();
    }
  </script>
</body>

</html>