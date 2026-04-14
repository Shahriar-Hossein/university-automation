<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if (!isset($_SESSION['teacher_login_id'])) {
    header('Location: ../teacherlogin.php');
    exit();
}

$teacher_login_user = $_SESSION['teacher_login_id'];

// Fetch teacher's information from the database
$teacher_sql = "SELECT * FROM teacher_db WHERE t_ID = '$teacher_login_user'";
$teacher_result = mysqli_query($conn, $teacher_sql);

if ($teacher_result->num_rows > 0) {
    $teacher = mysqli_fetch_assoc($teacher_result);
} else {
    die("Error: Unable to fetch teacher's information.");
}

// Update teacher's information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['teacher-name'];
    $email = $_POST['teacher-email'];
    $dob = $_POST['dob'];
    $contact_number = $_POST['contact-number'];
    $alt_contact_number = $_POST['alternate-contact-number'];
    $address = $_POST['address'];
    // $password = $_POST['password'];
    
    // File upload handling
    $photo = $_FILES['teacher-photo']['name'];
    $photo_tmp = $_FILES['teacher-photo']['tmp_name'];
    $upload_directory = '../images/teacher_images/';
    $uploaded_file = $upload_directory . basename($photo);

    if (move_uploaded_file($photo_tmp, $uploaded_file)) {
        $photo_path = $uploaded_file;
    } else {
        $photo_path = $teacher['t_ProfilePic'];
    }

    // Update query
    $update_sql = "UPDATE teacher_db SET 
        t_Name = '$name', 
        t_Email = '$email', 
        t_DateOfBirth = '$dob', 
        t_ContactNo = '$contact_number', 
        t_AltContactNo = '$alt_contact_number', 
        t_Address = '$address', 
        t_ProfilePic = '$photo_path'
        WHERE t_ID = '$teacher_login_user'";

    if (mysqli_query($conn, $update_sql)) {
        header('Location: profile.php');
        exit();
    } else {
        die("Error: Unable to update teacher's information.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile || Teachers</title>
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
                <span class="text">Edit Profile || <span style="font-weight: 300; margin-left: 10px;">Teachers</span></span>
            </div>
            <div class="main_workPanel" style="padding-bottom: 30px">
                <div class="main_workPanel_header">
                    <h3>Edit Profile</h3>
                </div>
                <div class="admin_monitor_add" style="position: relative;">
                    <div style="position: absolute; top:20px; right:20px;">
                        <a href="profile.php">
                            <i class='bx bx-arrow-back' style='font-size: 30px;'></i>
                        </a>
                    </div>

                    <form class="form" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="teacher-name">Teacher Name</label>
                            <input type="text" id="teacher-name" name="teacher-name" value="<?= htmlspecialchars($teacher['t_Name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="teacher-email">Teacher Email</label>
                            <input type="email" id="teacher-email" name="teacher-email" value="<?= htmlspecialchars($teacher['t_Email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required >
                                <option value="Male" <?= $teacher['t_Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $teacher['t_Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $teacher['t_Gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($teacher['t_DateOfBirth']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="teacher-id">Teacher ID</label>
                            <input type="text" id="teacher-id" name="teacher-id" value="<?= htmlspecialchars($teacher['t_ID']) ?>" disabled required>
                        </div>
                        <div class="form-group">
                            <label for="teacher-photo">Teacher Photo</label>
                            <input type="file" id="teacher-photo" name="teacher-photo" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="contact-number">Contact Number</label>
                            <input type="text" id="contact-number" name="contact-number" value="<?= htmlspecialchars($teacher['t_ContactNo']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alternate-contact-number">Alternate Contact Number</label>
                            <input type="text" id="alternate-contact-number" name="alternate-contact-number" value="<?= htmlspecialchars($teacher['t_AltContactNo']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="<?= htmlspecialchars($teacher['t_Address']) ?>" required>
                        </div>
                        <!-- <h3>Login Details</h3>
                        <div class="form-group">
                            <label for="user-name">User Name</label>
                            <input type="text" id="user-name" name="user-name" value="<?= htmlspecialchars($teacher['t_UserName']) ?>" disabled required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="<?= htmlspecialchars($teacher['t_Password']) ?>" >
                        </div> -->
                        <button type="submit" style="text-align: center; width: 400px" class="add-button">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('input[name="dob"]');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        });
    </script>
</body>

</html>
