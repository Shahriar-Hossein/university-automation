<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
  header('Location: http://localhost/sms/adminlogin.php');
  exit();
}
$admin_login_user = $_SESSION['admin_login_id'];

// Fetch all students with their attendance summary (total present/absent)
$sql = "SELECT s.s_ID as student_id, s.s_Name as student_name, 
        COUNT(CASE WHEN a.status = 1 THEN 1 END) AS present, 
        COUNT(CASE WHEN a.status = 0 THEN 1 END) AS absent
        FROM student_db s
        LEFT JOIN attendances a ON s.s_ID = a.student_id
        GROUP BY s.s_ID, s.s_Name
        ORDER BY s.s_ID ASC";

$result = mysqli_query($conn, $sql);
$students = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
}
echo '
<script>
console.log(' . count($students) . ');
</script>
';
?>
?>
<?php
    $page_title = 'Attendance || Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
  <i class='bx bx-menu'></i>
  <span class="text">Attendance || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
  <div>
    <h3>Attendance</h3>
  </div>
  <div class="admin_monitor">
    <div class="manage_admin_part">
      <h2>Student's Attendance Overview</h2>
      <form class="search-form" id="search-form">
        <h3>Search Student:</h3>
        <input type="text" id="student-id-input" placeholder="Search by Student ID" class="search-input">
        <button type="submit" class="search-button">Search</button>
      </form>

      <!-- Attendance Overview Table -->
      <div id="attendance-overview" class="table-group" style="margin-top: 10px; width: 1000px; margin: 0 auto; background: whitesmoke; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px;">
        <table>
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Present</th>
              <th>Absent</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="attendance-data">
          <!-- <tbody> -->
            <!-- Data will be populated here via AJAX -->
            <!-- Populate attendance data with PHP -->
            <?php if (!empty($students)) {
              foreach ($students as $student) {
                echo '<tr>';
                echo '<td>' . $student['student_id'] . '</td>';
                echo '<td>' . $student['student_name'] . '</td>';
                echo '<td>' . $student['present'] . '</td>';
                echo '<td>' . $student['absent'] . '</td>';
                echo '<td><a href="#" class="action-view" data-student-id="' . $student['student_id'] . '">👁️</a></td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="5">No students found.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

  <!-- Pop-up Modal -->
  <div id="popup" class="popup" style="display:none;">
    <div class="popup-content">
      <span class="close-button">&times;</span>
      <h2>Student's Attendance Overview</h2>
      <p>Student ID: <span id="student-id-popup"></span></p>
      <div class="table-group">
        <table>
          <thead>
            <tr>
              <th>Course Name</th>
              <th>Teacher Name</th>
              <th>Section</th>
              <th>Present</th>
              <th>Absent</th>
            </tr>
          </thead>
          <tbody id="course-attendance-details">
            <!-- Course details will be loaded here dynamically -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Handle search form submission
    document.getElementById('search-form').addEventListener('submit', function (event) {
      event.preventDefault();
      const studentId = document.getElementById('student-id-input').value;

      // AJAX to get the attendance overview for the student
      fetch(`../backend_requests/fetch_attendance_overview.php?student_id=${studentId}`)
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById('attendance-data');
          tbody.innerHTML = ''; // Clear previous data

          if (data.length > 0) {
            data.forEach(row => {
              tbody.innerHTML += `
                <tr>
                  <td>${row.student_id}</td>
                  <td>${row.student_name}</td>
                  <td>${row.present}</td>
                  <td>${row.absent}</td>
                  <td><a href="#" class="action-view" data-student-id="${row.student_id}">👁️</a></td>
                </tr>
              `;
            });

            document.getElementById('attendance-overview').style.display = 'block';
          } else {
            alert('No attendance data found for the student.');
          }
        });
    });

    // Handle click event on the action-view buttons to show the pop-up with details
    document.addEventListener('click', function (event) {
      if (event.target.classList.contains('action-view')) {
        event.preventDefault();
        const studentId = event.target.getAttribute('data-student-id');

        // AJAX to fetch detailed attendance data for the student
        fetch(`../backend_requests/fetch_course_attendance.php?student_id=${studentId}`)
          .then(response => response.json())
          .then(data => {
            const tbody = document.getElementById('course-attendance-details');
            tbody.innerHTML = ''; // Clear previous data

            if (data.length > 0) {
              data.forEach(row => {
                tbody.innerHTML += `
                  <tr>
                    <td>${row.course_name}</td>
                    <td>${row.teacher_name}</td>
                    <td>${row.section}</td>
                    <td>${row.present}</td>
                    <td>${row.absent}</td>
                  </tr>
                `;
              });

              document.getElementById('student-id-popup').innerText = studentId;
              document.getElementById('popup').style.display = 'block';
            }
          });
      }
    });

    // Close the pop-up
    document.querySelector('.close-button').addEventListener('click', function () {
      document.getElementById('popup').style.display = 'none';
    });
  </script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>
