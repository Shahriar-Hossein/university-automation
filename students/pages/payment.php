<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';
if (!isset($_SESSION['student_login_id'])) {
  if(isset($_GET['user_id'])) {
    $_SESSION['student_login_id'] = $_GET['user_id'];
  } else {
    header('Location: ../../studentlogin.php');
  }
}
$student_login_user = $_SESSION['student_login_id'];

// Query the sections table to get the course IDs
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
            student_course.student_id = " . $_SESSION['student_login_id'];
$sections_result = mysqli_query($conn, $sections_sql);
$total_amount = 0;
$section_details = [];
if ($sections_result->num_rows > 0) {
  while ($row = mysqli_fetch_assoc($sections_result)) {
    $section_details[] = $row;
    $total_amount += $row['c_hours'] * 3000;
  }
}

// Query the sections table to get the course IDs
$payment_sql = "SELECT * FROM payments WHERE student_id = " . $_SESSION['student_login_id'];
$payment_sql_result = mysqli_query($conn, $payment_sql);
if ($payment_sql_result->num_rows > 0) {
  $row = mysqli_fetch_assoc($payment_sql_result);
  $paid = $row['paid_amount'];
}
$due = $total_amount - $paid ?? 0;

// If status=1 is passed, update the paid_amount to be equal to total_amount
if (isset($_GET['status']) && $_GET['status']==1 ) {
  $update_sql = "UPDATE payments SET paid_amount = total_amount WHERE student_id = $student_login_user";
  if (mysqli_query($conn, $update_sql)) {
    echo "<script>alert('Payment completed.')</script>";
    echo "
    <script>
    setTimeout(()=>{
      window.location = window.location.pathname;
    },500)
    </script>
    ";
  } else {
    echo "<script>alert('Failed to make payment.')</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment ||Students</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once '../includes/sidebar.php'; ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Payment || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
      </div>
      <div class="main_workPanel">
        <!-- <div>
          <h3>View Course</h3>
        </div> -->
        <div class="admin_monitor">
          <div class="manage_admin_part" style="position: relative;">
            <h2>Enrolled Courses</h2>
            <div class="table-group">
              <table style="margin-top: 20px; width: 1500px;">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Credit Hour</th>
                    <th>Cost</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($section_details as $index => $section) {
                    echo '
                      <tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . $section['c_title'] . '</td>
                        <td>' . $section['c_code'] . '</td>
                        <td>' . $section['c_hours'] . '</td>
                        <td>' . $section['c_hours']*3000 . '</td>
                      </tr>
                    ';
                  }
                  ?>
                  <tr >
                    <td colspan="4" style="text-align:right">
                      <h4>
                        Total: 
                      </h4>
                    </td>
                    <td>
                      <div style="display:flex; align-items:center">
                        <h4>
                          <?= $total_amount ?>
                          BDT 
                        </h4>
                      </div>
                    </td>
                  </tr>
                  <tr >
                    <td colspan="4" style="text-align:right">
                      <h4>
                        Due: 
                      </h4>
                    </td>
                    <td>
                      <div style="display:flex; align-items:center">
                        <h4>
                          <?= $due ?>
                          BDT 
                        </h4>
                        <?php if($due > 0 ) : ?>
                          <a href="checkout.php?payable=<?php echo urlencode($due); ?>&student=<?php echo urlencode($student_login_user); ?>"
                            style="
                            background-color: #02788b; /* Green background */
                            border: none;              /* Remove border */
                            color: white;              /* White text */
                            padding: 2px 12px;        /* Some padding */
                            text-align: center;        /* Center the text */
                            text-decoration: none;     /* Remove underline */
                            display: inline-block;     /* Make the button inline */
                            font-size: 16px;           /* Increase font size */
                            margin: 5px 5px;            /* Add some space around */
                            cursor: pointer;           /* Pointer/hand icon on hover */
                            border-radius: 8px;        /* Rounded corners */
                            transition: background-color 0.3s ease;"
                            >Pay Now</a>
                            <?php else : ?>
                              <a href="#"
                              style="
                              background-color: #02788b; /* Green background */
                              border: none;              /* Remove border */
                              color: white;              /* White text */
                              padding: 2px 12px;        /* Some padding */
                              text-align: center;        /* Center the text */
                              text-decoration: none;     /* Remove underline */
                              display: inline-block;     /* Make the button inline */
                              font-size: 16px;           /* Increase font size */
                              margin: 5px 5px;            /* Add some space around */
                              cursor: pointer;           /* Pointer/hand icon on hover */
                              border-radius: 8px;        /* Rounded corners */
                              transition: background-color 0.3s ease;"
                              >Paid</a>
                            <?php endif ?>  
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <script src="../js/script.js"></script>
</body>

</html>