<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if(isset($_POST['TRegistration'])){
     $t_name = $_POST['teacher_name'];
     $t_email = $_POST['teacher_email'];
     $t_username = $_POST['tUser_name'];
     $t_password = $_POST['tPassword'];
     $t_gender = $_POST['teacher_gender'];
     $t_dob = $_POST['teacher_dob'];
     $t_contactnum = $_POST['tContact_number'];
     $t_altcontactnum = $_POST['tAltContact_number'];
     $t_address = $_POST['tAddress'];
     
    //  $s_photo = explode('.',$_FILES['student_photo'] ['name']);
    //  $ext = end($s_photo);
    //  $photo_name = $s_name.'.'.$ext;


     $t_sql = "insert into teacher_db(t_Name, t_UserName, t_Password, t_Email, t_Gender, t_DateOfBirth, t_ContactNo, t_AltContactNo, t_Address) values('$t_name', '$t_username', '$t_password', '$t_email', '$t_gender', '$t_dob', '$t_contactnum', '$t_altcontactnum', '$t_address')";

     if(mysqli_query($conn, $t_sql))
     {
        echo "<script>alert('New teacher data inserted')</script>";
        echo "<script>window.open('addTeachers.php','_self')</script>";
     }else{
        echo "<script>alert('Sorry! data has not inserted')</script>";
     }
}


?>
?>
<?php
    $page_title = 'Add Teachers ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Add Teachers || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel" style="padding-bottom: 30px">
    <div class="main_workPanel_header">
        <h3>Add Teachers</h3>
    </div>
    <div class="admin_monitor_add" style="margin: 0 auto;">
        <form class="form" action="" method="POST">
            <h2>Add Teachers</h2>
            <div class="form-group">
                <label for="teacher_name">Teacher Name</label>
                <input type="text" id="teacher_name" name="teacher_name" required>
            </div>
            <div class="form-group">
                <label for="teacher_email">Teacher Email</label>
                <input type="email" id="teacher_email" name="teacher_email" required>
            </div>
            
            <div class="form-group">
                <label for="gender">Department</label>
                <select id="gender" name="teacher_gender" required>
                    <option value="" disabled selected>Choose Department</option>
                    <option value="CSE">CSE</option>
                    <option value="ME">ME</option>
                    <option value="Civil">Civil</option>
                    <option value="EEE">EEE</option>
                    <option value="THM">THM</option>
                    <option value="Agriculture">THM</option>
                    <option value="English">English</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="teacher_gender" required>
                    <option value="" disabled selected>Choose Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="teacher_dob" name="teacher_dob" required>
            </div>
            
            <div class="form-group">
                <label for="teacher_photo">Teacher Photo</label>
                <input type="file" id="teacher_photo" name="teacher_photo" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="contact-number">Contact Number</label>
                <input type="text" id="tContact_number" name="tContact_number" required>
            </div>
            <div class="form-group">
                <label for="alternate-contact-number">Alternate Contact Number</label>
                <input type="text" id="tAltContact_number" name="tAltContact_number">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="tAddress" name="tAddress" required>
            </div>
            <h3>Login details</h3>
            <div class="form-group">
                <label for="user-name">User Name</label>
                <input type="text" id="tUser_name" name="tUser_name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="tPassword" name="tPassword" required>
            </div>
            <button type="submit" style="text-align: center; width: 400px" class="add-button" name="TRegistration">Add</button>
        </form>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('input[name="teacher_dob"]');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        });
    </script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>