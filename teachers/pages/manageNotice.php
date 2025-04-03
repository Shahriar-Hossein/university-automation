<?php
session_start();
require_once('../includes/dbconnection.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['teacher_login_id'])) {
  header('Location: ../teacherlogin.php');
  exit();
}

$teacher_login_user = $_SESSION['teacher_login_id'];

// Query to fetch notices created by the logged-in teacher
$notices_sql = "
  SELECT 
    notices.id as notice_id, 
    notices.title, 
    notices.message,
    notices.created_at, 
    sections.section, 
    course_db.c_title as course_name
  FROM 
    notices 
  JOIN 
    sections 
  ON 
    notices.section_id = sections.id
  JOIN 
    course_db 
  ON 
    sections.course_id = course_db.c_ID
  WHERE 
    sections.teacher_id = '$teacher_login_user'
  ORDER BY 
    notices.created_at DESC
";

// Store the query result in an array
$notices_result = mysqli_query($conn, $notices_sql);
$notices = [];
if (mysqli_num_rows($notices_result) > 0) {
  while ($row = mysqli_fetch_assoc($notices_result)) {
    $notices[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Notice || Teachers</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="website icon" type="png" href="../images/weblogo.png">
  <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
  <link rel="stylesheet" href="../pages/css/modal.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include_once('../includes/sidebar.php'); ?>
  <section class="home-section">
    <div class="home-content">
      <div class="dashboard_header">
        <i class='bx bx-menu'></i>
        <span class="text">Manage Notice || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
      </div>
      <div class="main_workPanel">
        <div>
          <h3>Manage Notice</h3>
        </div>
        <div class="admin_monitor">
          <div class="manage_admin_part">
            <h2>Manage Notice</h2>
            <div class="table-group" style="margin-top:20px">
              <table>
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Notice Title</th>
                    <th>Notice Message</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Notice Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($notices)): ?>
                    <?php foreach ($notices as $index => $notice): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($notice['title']) ?></td>
                        <td><?= htmlspecialchars($notice['message']) ?></td>
                        <td><?= htmlspecialchars($notice['course_name']) ?></td>
                        <td><?= htmlspecialchars($notice['section']) ?></td>
                        <td><?= htmlspecialchars($notice['created_at']) ?></td>
                        <td style="display:flex">
                          <button class="edit-button"
                            data-id="<?= $notice['notice_id'] ?>"
                            data-title="<?= htmlspecialchars($notice['title']) ?>"
                            data-message="<?= htmlspecialchars($notice['message']) ?>"
                            class="action-view">
                            ✏️
                          </button>
                          <p> / </p>
                          <button class="delete-button" data-id="<?= $notice['notice_id'] ?>" class="action-delete">🗑️</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" style="text-align:center;">No notices found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content" style="width: 500px; height: 300px; padding: 20px;">
      <span class="close" id="editClose">&times;</span>
      <h2>Edit Notice</h2>
      <form id="editForm" method="POST" action="../backend_requests/edit_notice.php">
        <input type="hidden" name="notice_id" id="editNoticeId">
        <div class="form-group">
          <label for="editNoticeTitle">Notice Title</label>
          <input type="text" id="editNoticeTitle" name="notice_title" required>
        </div>

        <div class="form-group">
          <label for="editNoticeMessage">Notice Message</label>
          <input type="text" id="editNoticeMessage" name="notice_message" required>
        </div>
        <button type="submit" class="modal-button">Save Changes</button>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <span class="close" id="deleteClose">&times;</span>
      <h2>Are you sure you want to delete this notice?</h2>
      <form id="deleteForm" method="POST" action="../backend_requests/delete_notice.php">
        <input type="hidden" name="notice_id" id="deleteNoticeId">
        <button type="submit" class="modal-button">Yes, Delete</button>
        <button type="button" class="modal-button" id="cancelDelete">Cancel</button>
      </form>
    </div>
  </div>

  <script src="../js/script.js"></script>
  <script>
    // Get modals
    var editModal = document.getElementById("editModal");
    var deleteModal = document.getElementById("deleteModal");

    // Get close buttons
    var editClose = document.getElementById("editClose");
    var deleteClose = document.getElementById("deleteClose");
    var cancelDelete = document.getElementById("cancelDelete");

    // Open Edit Modal
    document.querySelectorAll('.edit-button').forEach(button => {
      button.addEventListener('click', function() {
        var noticeId = this.getAttribute('data-id');
        var noticeTitle = this.getAttribute('data-title');
        var noticeMessage = this.getAttribute('data-message');
        document.getElementById('editNoticeId').value = noticeId;
        document.getElementById('editNoticeTitle').value = noticeTitle;
        document.getElementById('editNoticeMessage').value = noticeMessage;
        editModal.style.display = "block";
      });
    });

    // Open Delete Modal
    document.querySelectorAll('.delete-button').forEach(button => {
      button.addEventListener('click', function() {
        var noticeId = this.getAttribute('data-id');
        document.getElementById('deleteNoticeId').value = noticeId;
        deleteModal.style.display = "block";
      });
    });

    // Close Edit Modal
    editClose.onclick = function() {
      editModal.style.display = "none";
    };

    // Close Delete Modal
    deleteClose.onclick = function() {
      deleteModal.style.display = "none";
    };

    // Cancel Delete Action
    cancelDelete.onclick = function() {
      deleteModal.style.display = "none";
    };

    // Close modals when clicking outside of them
    window.onclick = function(event) {
      if (event.target == editModal) {
        editModal.style.display = "none";
      }
      if (event.target == deleteModal) {
        deleteModal.style.display = "none";
      }
    };
  </script>
</body>

</html>