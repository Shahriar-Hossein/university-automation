<?php
session_start();
require_once('../includes/dbconnection.php');
if (!isset($_SESSION['admin_login_id'])) {
  header('Location: http://localhost/sms/adminlogin.php');
}

// SQL query to fetch course list
$courses_sql = "SELECT * FROM course_db";
$result = mysqli_query($conn, $courses_sql);
$courses = [];
$error = null;
if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
  }
} else {
  $error = "No data found";
}

// SQL query to fetch teachers list
$teachers_sql = "SELECT * FROM teacher_db";
$result = mysqli_query($conn, $teachers_sql);
$teachers = [];
$error = null;
if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = mysqli_fetch_assoc($result)) {
    $teachers[] = $row;
  }
} else {
  $error = "No data found";
}
// options
$day_options = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$time_options = ['8:30-9:30', '9:35-10:35', '10:40-11:40', '11:45-12:45', '1:10-2:10', '2:15-3:15', '3:20-4:20', '4:25-5:25'];

// SQL query for rooms list
$rooms_sql = "SELECT * FROM rooms";
$result = mysqli_query($conn, $rooms_sql);
$rooms = [];
$error = null;
if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = mysqli_fetch_assoc($result)) {
    $rooms[] = $row;
  }
} else {
  $error = "No data found";
}

// handle live schedule
$schedules = [];
if (isset($_GET['course'])) {
  $course_id = $_GET['course'];
  $schedule_sql = "SELECT s.*, r.room_no as room, r.name 
                  FROM sections s
                  LEFT JOIN rooms r ON s.room_id = r.id
                  WHERE s.course_id = '$course_id'";

  $schedule_result = mysqli_query($conn, $schedule_sql);

  if ($schedule_result && mysqli_num_rows($schedule_result) > 0) {
    while ($row = mysqli_fetch_assoc($schedule_result)) {
      $schedules[] = $row;
    }
  }
}

// sort the schedules
$sorted_schedules = [];
if (!empty($schedules)) {
  // Define the order of days
  $day_order = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

  // Iterate over each schedule to split days and create separate entries
  foreach ($schedules as $schedule) {
    $days_array = explode(',', $schedule['days']);
    foreach ($days_array as $day) {
      $day = trim($day);
      $sorted_schedules[] = [
        'day' => $day,
        'section' => $schedule['section'],
        'time' => $schedule['time'],
        'room' => $schedule['room']
      ];
    }
  }

  // Sort the schedules based on the day order
  usort($sorted_schedules, function ($a, $b) use ($day_order) {
    return array_search($a['day'], $day_order) - array_search($b['day'], $day_order);
  });
}

// handle form submit and database query
if (isset($_POST['create_section'])) {
  // Retrieve form data
  $course_id = $_GET['course'];
  $teacher_id = $_POST['teacher'];
  $days =  $_POST['days'];
  $time = $_POST['time'];
  $section = 'A';
  $room = $_POST['room'];
  $error = null;

  // Check if all required fields are filled
  if (empty($course_id) || empty($teacher_id) || empty($days) || empty($time) || empty($room)) {
    $error = "All fields are required.";
  } else {

    foreach ($schedules as $schedule) {
      if ($schedule['section'] >= $section) {
        $ch = $schedule['section'];
        $section = chr(ord($ch) + 1);
      }
    }

    // Convert days array to string
    $days_string = implode(',', $days);

    // Validate time conflicts
    $conflict_found = false;
    foreach ($schedules as $schedule) {
      $existing_days = explode(',', $schedule['days']);
      $existing_time = $schedule['time'];

      // Check if there is a common day
      $common_days = array_intersect($days, $existing_days);
      if (!empty($common_days)) {
        // Check if the time overlaps
        if ($time == $existing_time) {
          $conflict_found = true;
          $error = "Time conflict detected with existing schedule.";
          break;
        }
      }
    }

    // If no conflicts found, insert the new section
    if (!$conflict_found) {
      $section_create_sql = "INSERT INTO sections (course_id, teacher_id, days, time, section, room_id) VALUES ('$course_id', '$teacher_id', '$days_string', '$time', '$section', '$room')";

      if (mysqli_query($conn, $section_create_sql)) {
        echo "<script>alert('New section created')</script>";
        echo "<script>window.open('manageSection.php','_self')</script>";
      } else {
        $error = "Sorry! Data has not been inserted.";
      }
    }
  }

  // Display error if any
  if ($error) {
    echo "<script>alert('$error')</script>";
  }
}

// Close connection
//$conn->close();

?>
?>
<?php
    $page_title = 'Manage Section ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
  <i class='bx bx-menu'></i>
  <span class="text">Manage Section || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div style="display: flex; justify-content: center;">
  <div class="admin_monitor_add">
    <h2>Manage Section</h2>
    <div class="form-group">
      <label for="course">Course</label>
      <form action="manageSection.php" method="GET">
        <select id="course" name="course" onChange="this.form.submit()" required>
          <option value="" disabled selected>Select Course</option>
          <?php
          foreach ($courses as $course) {
            $selected = isset($_GET['course']) && $_GET['course'] == $course['c_ID'] ? 'selected' : '';
            echo '<option value="' . $course['c_ID'] . '" ' . $selected . '>' . $course['c_title'] . '</option>';
          }
          ?>
        </select>
      </form>
    </div>
    <form class="form" action="" method="POST">

      <div class="form-group">
        <label for="teacher">Teacher</label>
        <select id="teacher" name="teacher" required>
          <option value="" disabled selected>Choose Teacher</option>
          <?php
          foreach ($teachers as $teacher) {
            echo '
              <option value="' . $teacher['t_ID'] . '" >' . $teacher['t_Name'] . ' </option>
            ';
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="section">Section</label>
        <select id="section" name="section">
          <option value="" disabled selected>Section name will be automatically assigned</option>
        </select>
      </div>

      <p>Select Time</p>
      <div class="form-group">
        <label for="time">Time</label>
        <select id="time" name="time" required>
          <option value="" disabled selected>Choose Time</option>
          <?php
          foreach ($time_options as $time) {
            echo '
              <option value="' . $time . '" >' . $time . ' </option>
            ';
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="room">Room No</label>
        <select id="room" name="room" required>
          <option value="" disabled selected>Choose Room</option>
          <?php
          foreach ($rooms as $room) :
          ?>
            <option value=<?= $room['id'] ?>> <?= $room['room_no'] ?> (<?= $room['name'] ?> )</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="schedule">Schedule</label>

        <div class="form-group checkbox-group">
          <?php
          foreach ($day_options as $day) {
            echo '
              <div class="checkbox_align">
                <label><input type="checkbox" name="days[]" value="' . $day . '">
                  <p style="width: 200px;">' . $day . '</p>
                </label>
              </div>
            ';
          }
          ?>
        </div>
      </div>


      <button style="text-align: center; width: 400px" type="submit" name="create_section" class="add-button">Submit</button>

    </form>
  </div>

  <div class="admin_monitor_add">
    <h2>Live Schedule</h2>
    <table>
      <thead>
        <tr>
          <th>Day</th>
          <th>Section</th>
          <th>Time</th>
          <th>Room No</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sorted_schedules)) : ?>
          <?php foreach ($sorted_schedules as $schedule) : ?>
            <tr>
              <td><?= $schedule['day'] ?></td>
              <td><?= $schedule['section'] ?></td>
              <td><?= $schedule['time'] ?></td>
              <td><?= $schedule['room'] ?? "unassigned" ?></td>
              <td><a href="#" class="action-delete">🗑️</a></td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="4">No schedule available for this course</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>