<?php
session_start();
require_once('../includes/dbconnection.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../teacherlogin.php');
    exit();
}

$teacher_login_user = $_SESSION['teacher_login_id'];
$success_msg = $error_msg = "";

// Fetch courses for the logged-in teacher
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and sanitize inputs
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $notice_title = mysqli_real_escape_string($conn, $_POST['notice-title']);
    $notice_msg = mysqli_real_escape_string($conn, $_POST['notmsg']);

    // Validate inputs (basic validation, extend as necessary)
    if (empty($notice_title) || empty($course_name) || empty($section) || empty($notice_msg)) {
        $error_msg = "All fields are required.";
    } else {
        // Insert notice into the database
        $insert_sql= "INSERT INTO notices (section_id, title, message,  created_at) 
                      VALUES ('$section', '$notice_title','$notice_msg', NOW())";

        if (mysqli_query($conn, $insert_sql)) {
            $success_msg = "Notice added successfully!";
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Notice || Teachers</title>
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
        <span class="text">Add Notice || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
      </div>
      <div class="main_workPanel">
        <div class="main_workPanel_header">
          <h3>Add Notice</h3>
        </div>
        <div class="admin_monitor_add">

          <!-- Display success or error messages -->
          <?php if ($success_msg): ?>
              <p id="success-message" style="color:green;"><?php echo $success_msg; ?></p>
          <?php endif; ?>

          <?php if ($error_msg): ?>
              <p style="color:red;"><?php echo $error_msg; ?></p>
          <?php endif; ?>

          <form class="form" method="POST" action="">
            <h3>Notice For</h3>
            
            <div class="form-group">
              <label for="course_name">Course Name</label>
              <select id="course_name" name="course_name" required onchange="fetchSections()">
                  <option value="" disabled selected>Choose Course Name</option>

                  <!-- Loop through all the courses with PHP -->
                  <?php foreach($courses as $course): ?>
                      <option value="<?= $course['course_id'] ?>"><?= $course['c_title'] ?></option>
                  <?php endforeach ?>
              </select>
            </div>

            <div class="form-group">
              <label for="section">Section</label>
              <select id="section" name="section" required>
                  <option value="" disabled selected>Choose Section</option>
                  <!-- This will be populated dynamically by JavaScript -->
              </select>
            </div>

            <h2>Add Notice</h2>
            <div class="form-group">
              <label for="notice-title">Notice Title</label>
              <input type="text" id="notice-title" name="notice-title" required style="margin: 0 auto;">
            </div>

            <div class="form-group">
              <label for="notice-msg">Notice Message</label>
              <textarea id="notice-msg" name="notmsg" class="notice-msgTxt" required></textarea>
            </div>

            <button style="text-align: center; width: 400px" type="submit" class="add-button">Add Notice</button>
          </form>

        </div>
      </div>
    </div>
  </section>

  <script src="../js/script.js"></script>
  <script>
  function fetchSections() {
    const courseId = document.getElementById("course_name").value;

    if (courseId) {
      // Make an AJAX request to fetch sections
      fetch(`fetch_sections.php?course_id=${courseId}`)
      .then(response => response.json())
      .then(data => {
        // Clear the section dropdown first
        const sectionDropdown = document.getElementById("section");
        sectionDropdown.innerHTML = '<option value="" disabled selected>Choose Section</option>';

        // Populate the sections from the server response
        data.sections.forEach(section => {
          const option = document.createElement("option");
          // Set the value as the section ID and display the section name
          option.value = section.id;
          option.textContent = section.name;
          sectionDropdown.appendChild(option);
        });
      })
      .catch(error => {
        console.error('Error fetching sections:', error);
      });
    }
  }

  // Hide success message after 5 seconds
  document.addEventListener("DOMContentLoaded", function () {
      const successMessage = document.getElementById("success-message");
      if (successMessage) {
          setTimeout(() => {
              successMessage.style.display = 'none';
          }, 5000); // 5000ms = 5 seconds
      }
  });
</script>
</body>
</html>
