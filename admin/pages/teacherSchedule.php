<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
  header('Location: http://localhost/sms/adminlogin.php');
}

// Fetch all teachers for the dropdown
$teachers = [];
$all_teachers_sql = mysqli_query($conn, "SELECT * FROM `teacher_db`");
while ($row = mysqli_fetch_assoc($all_teachers_sql)) {
  $teachers[] = $row;
}

// Initialize the schedule array grouped by days
$schedule_by_days = [
  'Saturday' => [],
  'Sunday' => [],
  'Monday' => [],
  'Tuesday' => [],
  'Wednesday' => [],
  'Thursday' => [],
  'Friday' => []

];

// Check if a teacher is selected and fetch their schedule
if (isset($_POST['teacher_id'])) {
  $teacher_id = $_POST['teacher_id'];

  // Fetch the schedule for the selected teacher
  $days = array_keys($schedule_by_days);  // ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
  foreach ($days as $day) {
    // Using the LIKE operator to match the day in the comma-separated 'days' field
    $schedule_sql = "SELECT s.days, s.time, c.c_title AS course, s.section, COALESCE(r.room_no, 'Unassigned') AS room
                     FROM sections s 
                     JOIN course_db c ON s.course_id = c.c_ID 
                     LEFT JOIN rooms r ON r.id = s.room_id
                     WHERE s.teacher_id = '$teacher_id' AND s.days LIKE '%$day%'";

    $result = mysqli_query($conn, $schedule_sql);

    // Fetch the schedule data for the current day
    while ($row = mysqli_fetch_assoc($result)) {
      $schedule_by_days[$day][] = $row;
    }
  }
}
?>
?>
<?php
    $page_title = 'Teacher Schedule || Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
  <i class='bx bx-menu'></i>
  <span class="text">Teacher Schedule || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
  <div>
    <h3>Teacher Schedule</h3>
  </div>

  <div class="form-group">
    <form action="" method="POST">
      <label for="teacher_id">Teacher</label>
      <select id="teacher_id" name="teacher_id" onchange="this.form.submit()" required>
        <option value="" disabled selected>Choose Teacher</option>
        <?php foreach ($teachers as $teacher) : ?>
          <option value="<?= $teacher['t_ID'] ?>" <?= isset($_POST['teacher_id']) && $_POST['teacher_id'] == $teacher['t_ID'] ? 'selected' : '' ?>>
            <?= $teacher['t_Name'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <div class="admin_monitor">
    <div class="manage_admin_part">
      <h2>Schedule</h2>
      <button onclick="downloadSchedule()" style="margin-bottom: 20px; padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Download / Print Schedule</button>
      <div class="table-group">
        <?php foreach ($schedule_by_days as $day => $entries) : ?>
          <?php if (!empty($entries)) : ?>
            <h3><?= $day ?></h3>
            <table id="scheduleTable">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Time</th>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Room No.</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($entries as $index => $entry) : ?>
                  <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($entry['time']) ?></td>
                    <td><?= htmlspecialchars($entry['course']) ?></td>
                    <td><?= htmlspecialchars($entry['section']) ?></td>
                    <td><?= htmlspecialchars($entry['room']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

  <script src="../js/script.js"></script>
  <script>
   function downloadSchedule() {
  var scheduleContent = `
    <h2 style="text-align: center;">IUBAT</h2>
    <h3 style="text-align: center;">Teacher Schedule</h3>
    `;

  // Get all tables for each day
  var allTables = document.querySelectorAll('.table-group table');
  allTables.forEach(function (table) {
    scheduleContent += `
    <h3 style="text-align: center;">${table.previousElementSibling.innerText}</h3>
    <table style="width: 800px; border-collapse: collapse;  margin: 0 auto;" border="1">
      ${table.innerHTML}
    </table>
    `;
  });

  var win = window.open('', '', 'width=900,height=700');
  win.document.write(`
        <html>
            <head>
                <title>Teacher Schedule</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h2, h3 { margin: 0; padding: 10px 0; }
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