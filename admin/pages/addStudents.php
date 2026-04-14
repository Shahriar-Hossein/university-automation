<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: ' . BASE_URL . 'adminlogin.php');
}
$admin_login_user = $_SESSION['admin_login_id'];

if (isset($_POST['SRegistration'])) {
    $s_name = $_POST['student_name'];
    $s_email = $_POST['student_email'];
    $s_username = $_POST['sUser_name'];
    $s_password = $_POST['sPassword'];
    $s_gender = $_POST['student_gender'];
    $s_dob = $_POST['student_dob'];
    $s_fname = $_POST['sFather_name'];
    $s_mname = $_POST['sMother_name'];
    $s_contactnum = $_POST['sContact_number'];
    $s_altcontactnum = $_POST['sAltContact_number'];
    $s_address = $_POST['sAddress'];

    //  $s_photo = explode('.',$_FILES['student_photo'] ['name']);
    //  $ext = end($s_photo);
    //  $photo_name = $s_name.'.'.$ext;


    $s_sql = "insert into student_db(s_Name, s_Email, s_UserName, s_Password, s_Gender, s_DateOfBirth,  s_FatherName, s_MotherName, s_ContactNo, s_AltContactNo, s_Address) values('$s_name', '$s_email', '$s_username', '$s_password', '$s_gender', '$s_dob', '$s_fname', '$s_mname', '$s_contactnum', '$s_altcontactnum', '$s_address')";

    if (mysqli_query($conn, $s_sql)) {
        echo "<script>alert('New student data inserted')</script>";
        echo "<script>window.open('addStudents.php','_self')</script>";
    } else {
        echo "<script>alert('Sorry! data has not inserted')</script>";
    }
}


?>

?>
<?php
    $page_title = 'Add Students ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Add Students || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel" style="padding-bottom: 30px">
    <div class="main_workPanel_header">
        <h3>Add Students</h3>
    </div>
    <div class="admin_monitor_add" style="margin: 0 auto;">
        <form class="form" action="" method="POST">
            <h2>Add Students</h2>
            <div class="form-group">
                <label for="student-name">Student Name</label>
                <input type="text" id="student_name" name="student_name" required>
            </div>
            <div class="form-group">
                <label for="student-email">Student Email</label>
                <input type="email" id="student_email" name="student_email" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="student_gender" required>
                    <option value="" disabled selected>Choose Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="student_dob" name="student_dob" required>
            </div>

            <div class="form-group">
                <label for="student-photo">Student Photo</label>
                <input type="file" id="student_photo" name="student_photo" accept="image/*">
            </div>
            <h3>Parents/Guardian's details</h3>
            <div class="form-group">
                <label for="father-name">Father's Name</label>
                <input type="text" id="sFather_name" name="sFather_name" required>
            </div>
            <div class="form-group">
                <label for="mother-name">Mother's Name</label>
                <input type="text" id="sMother_name" name="sMother_name" required>
            </div>
            <div class="form-group">
                <label for="contact-number">Contact Number</label>
                <input type="text" id="sContact_number" name="sContact_number" required>
            </div>
            <div class="form-group">
                <label for="sAltContact-number">Alternate Contact Number</label>
                <input type="text" id="sAltContact_number" name="sAltContact_number">
            </div>
            <div class="form-group">
                <label for="sAddress">Address</label>
                <input type="text" id="sAddress" name="sAddress" required>
            </div>
            <h3>Login details</h3>
            <div class="form-group">
                <label for="user-name">User Name</label>
                <input type="text" id="sUser_name" name="sUser_name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="sPassword" name="sPassword" required>
            </div>
            <button type="submit" name="SRegistration" style="text-align: center; width: 400px" class="add-button">Add</button>
        </form>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('input[name="student_dob"]');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        });
    </script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>