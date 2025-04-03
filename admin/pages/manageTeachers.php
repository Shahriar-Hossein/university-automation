<?php
session_start();
require_once('../includes/dbconnection.php');

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

$teachers = [];
$all_teachers_sql = mysqli_query($conn, "SELECT * FROM `teacher_db`");
while ($row = mysqli_fetch_assoc($all_teachers_sql)) {
  $teachers[] = $row;
}

if (isset($_GET['t_id_del'])) {
    $t_id_del = base64_decode($_GET['t_id_del']);
    mysqli_query($conn, "DELETE FROM `teacher_db` WHERE `t_ID` = '$t_id_del'");
    header('Location: manageTeachers.php');
    exit();
}

$teacher_information = null;
if (isset($_GET['edit_id'])) {
    $t_id_edit = base64_decode($_GET['edit_id']);
    $result = mysqli_query($conn, "SELECT * FROM teacher_db WHERE t_ID=$t_id_edit");
    $teacher_information = mysqli_fetch_array($result);
}

if (isset($_POST['T_Update'])) {
  $t_name = $_POST['teacher_name'];
  $t_email = $_POST['teacher_email'];
  $t_dob = $_POST['teacher_dob'];
  $t_contactnum = $_POST['tContact_number'];
  $t_altcontactnum = $_POST['tAltContact_number'];
  $t_address = $_POST['tAddress'];
  $t_department = $_POST['department'];

  $t_sql = 
  "UPDATE teacher_db SET 
  t_Name='$t_name',
  t_Email='$t_email',
  t_DateOfBirth='$t_dob',
  t_ContactNo='$t_contactnum',
  t_AltContactNo='$t_altcontactnum',
  t_department='$t_department',
  t_Address='$t_address'
  WHERE t_ID=$t_id_edit";

  if (mysqli_query($conn, $t_sql)) {
      echo "<script>alert('Teacher\'s data edited successfully...!')</script>";
      echo "<script>window.open('manageTeachers.php','_self')</script>";
  } else {
      echo "<script>alert('Sorry! data has not updated...!')</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Teachers ||Admin</title>
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
        <span class="text">Manage Teachers || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
      </div>
      <div class="main_workPanel">
        <div>
          <h3>Manage Teacher</h3>
        </div>
        <div class="admin_monitor">
          <div class="manage_admin_part">
            <h2>Manage Teacher</h2>
            <div class="table-group">
              <table>
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Teacher Name</th>
                    <th>Teacher Department</th>
                    <th>Teacher Email</th>
                    <th>Contact No.</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- PHP LOOP START -->
                  <?php foreach ($teachers as $index => $teacher) : ?>
                    <tr>
                      <td><?= $index + 1; ?></td>
                      <td><?= $teacher['t_Name']; ?></td>
                      <td><?= $teacher['t_department']; ?></td>
                      <td><?= $teacher['t_Email']; ?></td>
                      <td><?= $teacher['t_ContactNo']; ?></td>
                      <td style="display:flex">
                      <a href="javascript:void(0);" onclick="openModal('<?= base64_encode($teacher['t_ID'] ) ?>')" class="action-view">👁️</a>
                        <p> / </p>
                        <a href="javascript:void(0);" onclick="confirmDelete('<?= base64_encode($teacher['t_ID']); ?>', '<?= addslashes($teacher['t_Name']); ?>')" class="action-delete"> 🗑️</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <!-- PHP LOOP END -->

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="popup" class="modal" style="display:<?= isset($teacher_information) ? 'block' : 'none'; ?>; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="position:relative; display:flex; justify-content:center; align-items:center; height:100%;">
        <div class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content; position:relative;">
            <span onMouseOver="this.style.background='blue'; this.style.color='white';" onMouseOut="this.style.color='red'; this.style.background='white';" style="position: absolute; top:20px; right:20px; color:red; border-radius:50%; border: 2px solid black; padding-left:9px; padding-right:9px; cursor:pointer;" class="close-button">
                &times;
            </span>

        <form class="form" method="POST">
          <h4>Edit Profile</h4>
          <div class="form-group">
            <label for="teacher-name">Teacher Name</label>
            <input type="text" id="teacher-name" name="teacher_name" required value="<?= $teacher_information['t_Name']; ?>">
          </div>
          <div class="form-group">
            <label for="teacher-email">Teacher Email</label>
            <input type="email" id="teacher-email" name="teacher_email" required value="<?= $teacher_information['t_Email']; ?>">
          </div>

          <div class="form-group">
            <label for="department">Department</label>
            <select id="department" name="department" required>
              <option value="" disabled selected>Choose Department</option>
              <option value="CSE" <?= $teacher_information['t_department'] == "CSE" ? "Selected" : "" ?> >CSE</option>
              <option value="ME" <?= $teacher_information['t_department'] == "ME" ? "Selected" : "" ?>>ME</option>
              <option value="Civil" <?= $teacher_information['t_department'] == "Civil" ? "Selected" : "" ?>>Civil</option>
              <option value="EEE" <?= $teacher_information['t_department'] == "EEE" ? "Selected" : "" ?>>EEE</option>
              <option value="THM" <?= $teacher_information['t_department'] == "THM" ? "Selected" : "" ?>>THM</option>
              <option value="Agriculture" <?= $teacher_information['t_department'] == "Agriculture" ? "Selected" : "" ?>>Agriculture</option>
              <option value="English" <?= $teacher_information['t_department'] == "English" ? "Selected" : "" ?>>English</option>
            </select>
          </div>
        
          <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required disabled >
              <option value="" disabled selected>Choose Gender</option>
              <option value="Male" <?= $teacher_information['t_Gender'] == "Male" ? "Selected" : "" ?> >Male</option>
              <option value="Female" <?= $teacher_information['t_Gender'] == "Female" ? "Selected" : "" ?> >Female</option>
              <option value="Other" <?= $teacher_information['t_Gender'] == "Other" ? "Selected" : "" ?> >Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="teacher_dob" required value="<?php echo $teacher_information['t_DateOfBirth']; ?>">
          </div>

          <div class="form-group">
            <label for="teacher-photo">Teacher Photo</label>
            <input type="file" id="teacher-photo" name="teacher_photo" accept="image/*">
          </div>

          <div class="form-group">
            <label for="contact-number">Contact Number</label>
            <input type="text" id="contact-number" name="tContact_number" required value="<?php echo $teacher_information['t_ContactNo']; ?>">
          </div>
          <div class="form-group">
            <label for="alternate-contact-number">Alternate Contact Number</label>
            <input type="text" id="alternate-contact-number" name="tAltContact_number" value="<?php echo $teacher_information['t_AltContactNo']; ?>">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="tAddress" required value="<?php echo $teacher_information['t_Address']; ?>">
          </div>

          <button type="submit" style="text-align: center; width: 400px" class="add-button" name="T_Update">Update</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Modal Structure -->
  <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content">
      <div id="deleteModal" class="admin_monitor_add" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc;">
        <p id="teacherName"></p><br>
        <button id="confirmDelete" class="modalbtn" style="background-color: #ff0000; margin-right:10px;">Delete</button>
        <button id="cancelDelete" class="modalbtn" style="color: black;">Cancel</button>
      </div>
    </div>
  </div>

  <script src="../js/script.js"></script>

  <script>
    

    // ----- Delete modal function--------
    function confirmDelete(teacherId, teacherName) {
      document.getElementById('teacherName').innerText = "Are you sure? Do you want to remove '" + teacherName + "' from the Teacher List?";
      document.getElementById('confirmDelete').setAttribute('data-teacher-id', teacherId);
      document.getElementById('deleteModal').style.display = 'block';
      document.getElementById('modalOverlay').style.display = 'block';
    }

    document.getElementById('confirmDelete').addEventListener('click', function() {
      var teacherId = this.getAttribute('data-teacher-id');
      window.location.href = '?t_id_del=' + teacherId;
    });

    document.getElementById('cancelDelete').addEventListener('click', function() {
      document.getElementById('deleteModal').style.display = 'none';
      document.getElementById('modalOverlay').style.display = 'none';
    });

    document.getElementById('modalOverlay').addEventListener('click', function() {
      document.getElementById('deleteModal').style.display = 'none';
      document.getElementById('modalOverlay').style.display = 'none';
    });
  </script>
</body>

</html>