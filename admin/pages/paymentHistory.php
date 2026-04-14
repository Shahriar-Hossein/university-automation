<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
  header('Location: http://localhost/sms/adminlogin.php');
  exit();
}
$admin_login_user = $_SESSION['admin_login_id'];

// Fetch all students with their payment summary (total, paid, and due)
$sql = "SELECT s.s_ID as student_id, s.s_Name as student_name, 
        COALESCE(p.total_amount, 0) AS total_amount, 
        COALESCE(p.paid_amount, 0) AS paid_amount,
        COALESCE(p.total_amount - p.paid_amount, 0) AS due_amount
        FROM student_db s
        LEFT JOIN payments p ON s.s_ID = p.student_id
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
    $page_title = 'Payment History || Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
  <i class='bx bx-menu'></i>
  <span class="text">Manage Studnets || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
  <div>
    <h3>Payment History</h3>
  </div>
  <div class="admin_monitor">
    <div class="manage_admin_part">
      <h2>Student's Payment Overview</h2>
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
              <th>Payment Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="payment-data">
            <?php if (!empty($students)) {
              foreach ($students as $student) {
                $status = $student['due_amount'] > 0 ? "Due" : "Paid" ;
                echo '<tr>';
                echo '<td>' . $student['student_id'] . '</td>';
                echo '<td>' . $student['student_name'] . '</td>';
                echo '<td>' . $status . '</td>';
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
    <h2>Student's Payment Overview</h2>
    <h4>Student Name: <span id="student-name-popup"></span></h4>
    <p>Student ID: <span id="student-id-popup"></span></p>
    <hr>
    <div class="table-group">
      <table>
        <thead>
          <tr>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
          </tr>
        </thead>
        <tbody id="payment-details">
          <!-- Payment details will be loaded here dynamically -->
        </tbody>
      </table>
    </div>
  </div>
</div>


<script>
  // Handle search form submission
  document.getElementById('search-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const studentId = document.getElementById('student-id-input').value;
    
    // AJAX to get the payment overview for the student
    fetch(`../backend_requests/fetch_payment_details.php?student_id=${studentId}`)
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById('payment-data');
      tbody.innerHTML = ''; // Clear previous data
      
      if (data) {
        const status = data.due_amount > 0 ? 'Due' : 'Paid';
        tbody.innerHTML = `
        <tr>
        <td>${studentId}</td>
        <td>${data.student_name}</td>
        <td>${status}</td>
        <td><a href="#" class="action-view" data-student-id="${studentId}">👁️</a></td>
        </tr>
        `;
        
        document.getElementById('attendance-overview').style.display = 'block';
      } else {
        alert('No payment data found for the student.');
      }
    });
  });
  
  
  // Handle click event on the action-view buttons to show the pop-up with payment details
  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('action-view')) {
      event.preventDefault();
      const studentId = event.target.getAttribute('data-student-id');
      
      // AJAX to fetch detailed payment data for the student
      fetch(`../backend_requests/fetch_payment_details.php?student_id=${studentId}`)
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById('payment-details');
        tbody.innerHTML = ''; // Clear previous data
        
        if (data) {
          tbody.innerHTML = `
          <tr>
          <td>$${data.total_amount}</td>
          <td>$${data.paid_amount}</td>
          <td>$${data.due_amount}</td>
          </tr>
          `;
          
          document.getElementById('student-id-popup').innerText = studentId;
          document.getElementById('student-name-popup').innerText = data.student_name;
          document.getElementById('popup').style.display = 'block';
        } else {
          alert('No payment data found for this student.');
        }
      });
    }
  });
  
  // Close the pop-up
  document.querySelector('.close-button').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
  });
  
</script>
<script src="../js/script-alt.js"></script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>