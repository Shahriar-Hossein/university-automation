<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';
if (!isset($_SESSION['student_login_id'])) {
    header('Location: ../studentlogin.php');
}
$student_login_user = $_SESSION['student_login_id'];

// Query the sections table to get the course details
$sections_sql = "
        SELECT 
            student_course.*,
            sections.*, 
            course_db.*
        FROM 
            student_course 
        JOIN 
            course_db 
        ON 
            student_course.course_id = course_db.c_ID 
        JOIN 
            sections
        ON 
            student_course.section_id = sections.id
        WHERE 
            student_course.student_id = " . $_SESSION['student_login_id'] . "
        AND student_course.status = 'in-progress'";

$sections_result = mysqli_query($conn, $sections_sql);

$section_details = [];
if ($sections_result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($sections_result)) {
        $section_details[] = $row;
    }
}

// Define the order of days
$days_order = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday"];

// Prepare the schedule array
$schedule = [];
foreach ($days_order as $day) {
    $schedule[$day] = [];
}

foreach ($section_details as $section) {
    $days = explode(',', $section['days']);
    foreach ($days as $day) {
        if (in_array($day, $days_order)) {
            $schedule[$day][] = $section;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Schedule || Students</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once('../includes/sidebar.php'); ?>
    <section class="home-section">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">Course Schedule || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
            </div>
            <div class="main_workPanel">
                <div>
                    <h3>Course Schedule</h3>
                </div>
                <div class="admin_monitor">
                    <div class="manage_admin_part" style="position: relative;">
                        <h2>Course Schedule</h2>
                        <button onclick="downloadSchedule()" style="margin-bottom: 20px; padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Download / Print Schedule</button>
                        <div class="table-group">
                            <table id="scheduleTable" style="margin-top: 20px; width: 1500px;">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                        <th>Section Name</th>
                                        <th>Room No.</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($days_order as $day) {
                                        foreach ($schedule[$day] as $index => $section) {
                                            echo '
                                            <tr>
                                                <td>' . $day . '</td>
                                                <td>' . $section['c_title'] . '</td>
                                                <td>' . $section['c_code'] . '</td>
                                                <td>' . $section['section'] . '</td>
                                                <td>' . $section['room'] . '</td>
                                                <td>' . $section['time'] . '</td>
                                            </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <script>
        function downloadSchedule() {
    var scheduleContent = `
    <h2 style="text-align: center;">IUBAT</h2>
    <h3 style="text-align: center;">Course Schedule</h3>
    <table style="width: 800px; border-collapse: collapse;  margin: 0 auto;" border="1">
        ${document.getElementById('scheduleTable').innerHTML}
    </table>
    `;

    var win = window.open('', '', 'width=900,height=700');
    win.document.write(`
        <html>
            <head>
                <title>Course Schedule</title>
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
</body>

</html>