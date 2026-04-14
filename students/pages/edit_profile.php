<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';
if (!isset($_SESSION['student_login_id'])) {
    header('Location: ../studentlogin.php');
}
$student_login_user = $_SESSION['student_login_id'];

$student_sql = "SELECT * FROM student_db WHERE s_ID = $student_login_user";
$student_result = mysqli_query($conn, $student_sql);

$student_row = null;
if ($student_result->num_rows > 0) {
    $student_row = mysqli_fetch_assoc($student_result);
}

if (isset($_POST['student_edit'])){
    $student_id = $student_login_user;
    $student_name = $_POST['name'];
    $student_email = $_POST['email'];
    $student_phone = $_POST['contact_number'];
    $student_address = $_POST['address'];
    $student_father_name = $_POST['father_name'];
    $student_mother_name = $_POST['mother_name'];
    $student_gender = $_POST['gender'];
    $student_birthdate = $_POST['dob'];
    $student_photo = $_FILES['photo']['name']; // Assuming file upload for photo

    // Handle file upload if a new photo is uploaded
    if (!empty($student_photo)) {
        $target_dir = "../images/student_photos/";
        $target_file = $target_dir . basename($student_photo);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

        // Update student data including photo
        $update_sql = "UPDATE student_db SET 
            s_Name = '$student_name', 
            s_Email = '$student_email', 
            s_ContactNo = '$student_phone', 
            s_Address = '$student_address', 
            s_FatherName = '$student_father_name', 
            s_MotherName = '$student_mother_name', 
            s_Gender = '$student_gender', 
            s_DateOfBirth = '$student_birthdate',
            s_Photo = '$target_file' 
            WHERE s_ID = $student_id";
    } else {
        // Update student data without changing the photo
        $update_sql = "UPDATE student_db SET 
            s_Name = '$student_name', 
            s_Email = '$student_email', 
            s_ContactNo = '$student_phone', 
            s_Address = '$student_address', 
            s_FatherName = '$student_father_name', 
            s_MotherName = '$student_mother_name', 
            s_Gender = '$student_gender', 
            s_DateOfBirth = '$student_birthdate' 
            WHERE s_ID = $student_id";
    }

    // Execute the update query
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Profile updated successfully')</script>";
        echo "<script>window.open('profile.php','_self')</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again.')</script>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile ||Students</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once('../includes/sidebar.php'); ?>
    <section class="home-section" style="height: 1380px">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">Edit Profile || <span style="font-weight: 300; margin-left: 10px;">Students</span></span>
            </div>
            <div class="main_workPanel" style="padding-bottom: 30px">
                <div class="main_workPanel_header">
                    <h3>Student's Profile</h3>
                </div>

                <div class="admin_monitor_add" style="position: relative;">
                    <div style="position: absolute; top:20px; right:20px;"><a href="http://localhost/sms/students/pages/profile.php"><i class='bx bx-arrow-back' style='font-size: 30px;'></i></a></div>

                    <form class="form" method="POST" enctype="multipart/form-data"> >
                        <h2>Edit Profile</h2>

                        <div class="form-group">
                            <label for="student-name">Student Name</label>
                            <input type="text" id="student-name" name="name" value='<?= $student_row ? $student_row['s_Name'] : "" ?>' >
                        </div>

                        <div class="form-group">
                            <label for="student-email">Student Email</label>
                            <input type="email" id="student-email" name="email" value='<?= $student_row ? $student_row['s_Email'] : "" ?>' >
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="" disabled selected>Choose Gender</option>
                                <option value="Male" <?= $student_row && $student_row['s_Gender'] == 'Male' ? "selected" : "" ?> >Male</option>
                                <option value="Female" <?= $student_row && $student_row['s_Gender'] == 'Female' ? "selected" : "" ?> >Female</option>
                                <option value="Other" <?= $student_row && $student_row['s_Gender'] == 'Other' ? "selected" : "" ?> >Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value='<?= $student_row ? $student_row['s_DateOfBirth'] : "" ?>' required>
                        </div>

                        <div class="form-group">
                            <label for="student-id">Student ID</label>
                            <input type="text" id="student-id" name="id" value='<?= $student_row ? $student_row['s_ID'] : "" ?>' disabled >
                        </div>

                        <div class="form-group">
                            <label for="student-photo">Student Photo</label>
                            <input type="file" id="student-photo" name="photo" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="father-name">Father Name</label>
                            <input type="text" id="father-name" name="father_name" value='<?= $student_row ? $student_row['s_FatherName'] : "" ?>' >
                        </div>

                        <div class="form-group">
                            <label for="mother-name">Mother Name</label>
                            <input type="text" id="mother-name" name="mother_name" value='<?= $student_row ? $student_row['s_MotherName'] : "" ?>' >
                        </div>

                        <div class="form-group">
                            <label for="contact-number">Contact Number</label>
                            <input type="text" id="contact-number" name="contact_number" value='<?= $student_row ? $student_row['s_ContactNo'] : "" ?>' >
                        </div>

                        <div class="form-group">
                            <label for="alternate-contact-number">Alternate Contact Number</label>
                            <input type="text" id="alternate-contact-number" name="alternate_contact_number" value='<?= $student_row ? $student_row['s_AltContactNo'] : "" ?>'>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" required value='<?= $student_row ? $student_row['s_Address'] : "" ?>'>
                        </div>

                        <!-- <h3>Login details</h3>
                        <div class="form-group">
                            <label for="user-name">User Name</label>
                            <input type="text" id="user-name" name="user-name" value="mehrab_ahmed" disabled required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="*********" disabled>
                        </div> -->
                        <button type="submit" style="text-align: center; width: 400px" class="add-button" name="student_edit">Submit</button>
                    </form>
                </div>


            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var dateInput = document.querySelector('input[name="dob"]');
        //     var today = new Date().toISOString().split('T')[0];
        //     dateInput.setAttribute('max', today);
        // });
    </script>
</body>

</html>