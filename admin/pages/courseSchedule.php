<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

// Fetch courses from the database
$courses = [];
$sections = [];

// Fetch course details (ID and title) for the dropdown
$course_sql = "SELECT c_ID, c_title FROM course_db";
$course_result = mysqli_query($conn, $course_sql);
while ($row = mysqli_fetch_assoc($course_result)) {
    $courses[] = $row;
}

// Fetch sections for the dropdown based on selected course
if (isset($_POST['course_id'])) {
    $selected_course_id = $_POST['course_id'];
    $section_sql = "SELECT DISTINCT section FROM sections WHERE course_id = '$selected_course_id'";
    $section_result = mysqli_query($conn, $section_sql);
    while ($row = mysqli_fetch_assoc($section_result)) {
        $sections[] = $row['section'];
    }
}

// Initialize the schedule array
$schedule = [];

// Fetch the schedule for the selected course and section
if (isset($_POST['course_id']) && isset($_POST['section'])) {
    $course_id = $_POST['course_id'];
    $section = $_POST['section'];

    $schedule_sql= "SELECT s.days, s.time, COALESCE(r.room_no, 'Unassigned') AS room_no 
                    FROM sections s 
                    LEFT JOIN rooms r ON r.id = s.room_id
                    WHERE s.course_id = '$course_id' 
                    AND s.section = '$section'";
    $result = mysqli_query($conn, $schedule_sql);

    // Process the schedule data
    while ($row = mysqli_fetch_assoc($result)) {
        $days = explode(',', $row['days']); // Split days by comma
        foreach ($days as $day) {
            $schedule[$day][] = [
                'time' => $row['time'],
                'room' => $row['room_no']
            ];
        }
    }
}
?>
?>
<?php
    $page_title = 'Course Schedule || Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Course Schedule || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
    <div>
        <h3>Course Schedule</h3>
    </div>

    <div class="form-group">
        <form action="" method="POST" id="course-form">
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id" onchange="this.form.submit()" required>
                <option value="" disabled selected>Select Course</option>
                <?php foreach ($courses as $course) : ?>
                    <option value="<?= $course['c_ID'] ?>" <?= isset($_POST['course_id']) && $_POST['course_id'] == $course['c_ID'] ? 'selected' : '' ?>>
                        <?= $course['c_title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($sections)) : ?>
                <label for="section">Section</label>
                <select id="section" name="section" onchange="this.form.submit()" required>
                    <option value="" disabled selected>Select Section</option>
                    <?php foreach ($sections as $section) : ?>
                        <option value="<?= $section ?>" <?= isset($_POST['section']) && $_POST['section'] == $section ? 'selected' : '' ?>>
                            <?= $section ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </form>
    </div>

    <div class="admin_monitor">
        <div class="manage_admin_part">
            <h2>Course Schedule</h2>
            <!-- <button onclick="downloadSchedule()" style="margin-bottom: 20px; padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Download / Print Schedule</button> -->
            <div class="table-group">
                <table id="scheduleTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($schedule)) : ?>
                            <?php foreach ($schedule as $day => $entries) : ?>
                                <?php foreach ($entries as $entry) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($day) ?></td>
                                        <td><?= htmlspecialchars($entry['time']) ?></td>
                                        <td><?= htmlspecialchars($entry['room']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3">No schedule available for this course and section</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

    <script src="../js/script.js"></script>
    <script>
  function downloadSchedule() {
  var scheduleContent = `
    <h2 style="text-align: center;">IUBAT</h2>
    <h3 style="text-align: center;">Course Schedule</h3>
    <table style="width: 800px; border-collapse: collapse; margin: 0 auto;" border="1">
      <thead>
        <tr>
          <th>Day</th>
          <th>Time</th>
          <th>Room No.</th>
        </tr>
      </thead>
      <tbody>
  `;

  // Iterate through the table rows
  var scheduleTableRows = document.querySelectorAll('#scheduleTable tbody tr');
  scheduleTableRows.forEach(function (row) {
    scheduleContent += `
      <tr>
        ${row.innerHTML}
      </tr>
    `;
  });

  scheduleContent += `
      </tbody>
    </table>
  `;

  var win = window.open('', '', 'width=900,height=700');
  win.document.write(`
        <html>
            <head>
                <title>Course Schedule</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h2, h3 { margin: 0; padding: 10px 0; text-align: center; }
                    table { width: 800px; border-collapse: collapse; margin: 0 auto; }
                    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                ${scheduleContent}
            </body>
        </html>
    `);
  win.document.close();

  // Print the schedule or save as PDF
  win.print();
}


    </script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>
