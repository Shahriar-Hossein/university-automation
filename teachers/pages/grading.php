<?php
session_start();
require_once('../includes/dbconnection.php');

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
    RIGHT JOIN 
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

// Handle form submission
$selected_course_id = $_POST['course_id'] ?? null;
$selected_section_id = $_POST['section_id'] ?? null;

$students = [];
$grades_records = [];

if ($selected_course_id && $selected_section_id) {
  // Fetch students for the selected course and section
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

  // Check if grades already exist for the selected course and section
  $grades_sql = "
        SELECT 
            student_id, 
            marks,
            grade
        FROM 
            grades 
        WHERE 
            course_id = $selected_course_id 
            AND section_id = $selected_section_id";

  $grades_result = mysqli_query($conn, $grades_sql);

  if ($grades_result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($grades_result)) {
      $marks_records[$row['student_id']] = $row['marks'];
      $grades_records[$row['student_id']] = $row['grade'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grading || Teachers</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once('../includes/sidebar.php'); ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Grading || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
      </div>
      <div class="main_workPanel">
        <div>
          <h3>Grading</h3>
        </div>
        <div class="admin_monitor">
          <div class="manage_admin_part">
            <h2>Grade Students Overview</h2>
            <form method="POST" action="">
              <div class="form-group" style="display:flex; margin-top: 20px;">
                <div>
                  <label for="course">Course Name</label>
                  <select id="course" name="course_id" required onchange="this.form.submit()">
                    <option value="" disabled selected>Choose Course Name</option>
                    <?php
                    foreach ($courses as $course) {
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
              </div>
            </form>

            <?php if ($students): ?>
              <form method="POST" action="../backend_requests/save_grades.php">
                <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                <input type="hidden" name="section_id" value="<?php echo $selected_section_id; ?>">
                <div style="margin-top: 10px; width: 60%; margin: 0 auto; background: whitesmoke; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px;" class="table-group">
                  <h1 style="text-align: center; padding: 20px 0;">
                    Assign Grades
                  </h1>
                  <table>
                    <thead>
                      <tr>
                        <th style="text-align: center;">SI No.</th>
                        <th style="text-align: center;">Student ID</th>
                        <th style="text-align: center;">Student Name</th>
                        <th style="text-align: center;">Total Number</th>
                        <th style="text-align: center;">Grade</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($students as $index => $student) : ?>
                        <tr>
                          <td style="text-align: center;"><?= $index + 1 ?> </td>
                          <td style="text-align: center;"><?= $student['student_id'] ?></td>
                          <td style="text-align: center;"><?= $student['s_Name'] ?></td>
                          <td style="text-align: center;">
                            <input type="number" name="total_number[<?= $student['student_id'] ?>]"
                              value="<?= $marks_records[$student['student_id']] ?? '' ?>"
                              class="total-number-input"
                              data-student-id="<?= $student['student_id'] ?>"
                              min="0" max="100"
                              required
                              style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; width: 80px;"
                              onchange="assignGrade(this)">
                          </td>
                          <td style="text-align: center;">
                            <select name="grades[<?= $student['student_id'] ?>]"
                              id="grade_<?= $student['student_id'] ?>"
                              disabled
                              style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; background-color: #f9f9f9; cursor: pointer;"
                            >
                              <option 
                                value="" 
                              >
                                Select Grade
                              </option>
                              <?php 
                              $grades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'F']; 
                              ?>
                              <?php foreach ($grades as $grade): ?>
                                <option 
                                  value="<?= $grade ?>" 
                                  <?= isset($grades_records[$student['student_id']]) && 
                                  $grades_records[$student['student_id']] == $grade ? 'selected' : '' ?>
                                >
                                    <?= $grade ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </td>

                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                  <button type="submit" class="btn btn-primary"
                    style="background-color: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">
                    Submit Grades
                  </button>
                </div>
              </form>
            <?php else: ?>
              <p>No students found for the selected course and section.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../js/script.js"></script>
  <script>
    function assignGrade(input) {
      const totalNumber = parseInt(input.value, 10);
      const studentId = input.getAttribute('data-student-id');
      const gradeSelect = document.getElementById('grade_' + studentId);

      if (totalNumber >= 80 && totalNumber <= 100) {
        gradeSelect.value = 'A+';
      } else if (totalNumber >= 75 && totalNumber <= 79) {
        gradeSelect.value = 'A';
      } else if (totalNumber >= 70 && totalNumber <= 74) {
        gradeSelect.value = 'A-';
      } else if (totalNumber >= 65 && totalNumber <= 69) {
        gradeSelect.value = 'B+';
      } else if (totalNumber >= 60 && totalNumber <= 64) {
        gradeSelect.value = 'B';
      } else if (totalNumber >= 55 && totalNumber <= 59) {
        gradeSelect.value = 'B-';
      } else if (totalNumber >= 50 && totalNumber <= 54) {
        gradeSelect.value = 'C+';
      } else if (totalNumber >= 45 && totalNumber <= 49) {
        gradeSelect.value = 'C';
      } else if (totalNumber >= 40 && totalNumber <= 44) {
        gradeSelect.value = 'D';
      } else {
        gradeSelect.value = 'F';
      }
    }
  </script>
</body>

</html>