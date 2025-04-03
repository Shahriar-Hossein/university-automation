<?php
session_start();
require_once('../includes/dbconnection.php');

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

if (isset($_GET['s_id_del'])) {
    $s_id_del = base64_decode($_GET['s_id_del']);
    mysqli_query($conn, "DELETE FROM `student_db` WHERE `s_ID` = '$s_id_del'");
    header('Location: manageStudents.php');
    exit();
}
$student = null;
if (isset($_GET['edit_id'])) {
    $s_id_edit = base64_decode($_GET['edit_id']);
    $sql = "Select * from `student_db` where `s_ID`='$s_id_edit'";


    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);

    $sGender = $student['s_Gender'];
    $sDateofBirth = $student['s_DateOfBirth'];
    $sFatherName = $student['s_FatherName'];
    $sMotherName = $student['s_MotherName'];
    $sContactNo = $student['s_ContactNo'];
    $sAltContactNo = $student['s_AltContactNo'];
    $sAddress = $student['s_Address'];
}

if (isset($_POST['S_Update'])) {
    $s_name = $_POST['student_name'];
    $s_email = $_POST['student_email'];
    $s_gender = $_POST['student_gender'];
    $s_dob = $_POST['student_dob'];
    $s_fname = $_POST['sFather_name'];
    $s_mname = $_POST['sMother_name'];
    $s_contactnum = $_POST['sContact_number'];
    $s_altcontactnum = $_POST['sAltContact_number'];
    $s_address = $_POST['sAddress'];

    $s_sql = "update `student_db` set s_Name='$s_name', s_Email='$s_email', s_Gender='$s_gender', s_DateOfBirth='$s_dob',  s_FatherName='$s_fname', s_MotherName='$s_mname', s_ContactNo='$s_contactnum', s_AltContactNo='$s_altcontactnum', s_Address='$s_address' where s_ID=$s_id_edit";

    if (mysqli_query($conn, $s_sql)) {
        echo "<script>alert('Student data edited successfully...!')</script>";
        echo "<script>window.open('manageStudents.php','_self')</script>";
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
    <title>Manage Students ||Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once('../includes/sidebar.php'); ?>
    <section class="home-section" style="position: relative;">
        <div class="home-content">
            <div class="dashboard_header">
                <i class='bx bx-menu'></i>
                <span class="text">Manage Students || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
            </div>
            <div class="main_workPanel">
                <div style="display: flex; justify-content:space-between; align-items:center;">
                    <h3>Manage Students</h3>
                    <input type="text" id="searchInput" placeholder="Search by student name..." style="margin-bottom: 10px; padding: 8px; width: 300px; border-radius:15px; border: 1px solid #4637bb90;">
                </div>
                <div class="admin_monitor">
                    <div class="manage_admin_part">
                        <h2>Manage Students</h2>
                        <div class="table-group">
                            <table>
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Student Email</th>
                                        <th>Contact No.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                    <?php
                                    $all_students = mysqli_query($conn, "SELECT * FROM `student_db`");
                                    $serial_no = 1;
                                    while ($row = mysqli_fetch_assoc($all_students)) {
                                    ?>

                                        <tr>
                                            <td><?php echo $serial_no++; ?></td>
                                            <td><?php echo $row['s_ID']; ?></td>
                                            <td><?php echo $row['s_Name']; ?></td>
                                            <td><?php echo $row['s_Email']; ?></td>
                                            <td><?php echo $row['s_ContactNo']; ?></td>
                                            <td style="display:flex">
                                                <a href="javascript:void(0);" onclick="openModal('<?= base64_encode($row['s_ID']); ?>')" class="action-view">👁️</a>
                                                <p> / </p>
                                                <a href="javascript:void(0);" onclick="confirmDelete('<?= base64_encode($row['s_ID']); ?>', '<?= addslashes($row['s_Name']); ?>')" class="action-delete"> 🗑️</a>
                                            </td>
                                        </tr>

                                    <?php
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
    <!-- Modal Structure -->
    <div id="popup" class="modal" style="display:<?= isset($student) ? 'block' : 'none'; ?>; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
        <div class="modal-content" style=" position: relative; display: flex; justify-content: center; align-items: center; overflow-y: auto; height: 100%; padding: 20px;">
            <div class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content; position:relative;">
                <span onMouseOver="this.style.background='blue'; this.style.color='white';" onMouseOut="this.style.color='red'; this.style.background='white';" style="position: absolute; top:20px; right:20px; color:red; border-radius:50%; border: 2px solid black; padding-left:9px; padding-right:9px; cursor:pointer;" class="close-button">
                    &times;
                </span>

                <form class="form" action="" method="POST">
                    <h4>Edit Profile</h4>
                    <div class="form-group">
                        <label for="student-name">Student Name</label>
                        <input type="text" id="student_name" name="student_name" required value="<?= $student['s_Name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="student-email">Student Email</label>
                        <input type="email" id="student_email" name="student_email" required value="<?= $student['s_Email'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="student_gender" required disabled>
                            <option value="" disabled selected>Choose Gender</option>
                            <option value="Male" <?= $student['s_Gender'] === "Male" ? "selected" : "" ?>>Male</option>
                            <option value="Female" <?= $student['s_Gender'] === "Female" ? "selected" : "" ?>>Female</option>
                            <option value="Other" <?= $student['s_Gender'] === "Other" ? "selected" : "" ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="student_dob" name="student_dob" required value="<?php echo $sDateofBirth ?>">
                    </div>

                    <div class="form-group">
                        <label for="student-photo">Student Photo</label>
                        <input type="file" id="student_photo" name="student_photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="father-name">Father's Name</label>
                        <input type="text" id="sFather_name" name="sFather_name" required value="<?php echo $sFatherName ?>">
                    </div>
                    <div class="form-group">
                        <label for="mother-name">Mother's Name</label>
                        <input type="text" id="sMother_name" name="sMother_name" required value="<?php echo $sMotherName ?>">
                    </div>
                    <div class="form-group">
                        <label for="contact-number">Contact Number</label>
                        <input type="text" id="sContact_number" name="sContact_number" required value="<?php echo $sContactNo ?>">
                    </div>
                    <div class="form-group">
                        <label for="sAltContact-number">Alternate Contact Number</label>
                        <input type="text" id="sAltContact_number" name="sAltContact_number" value="<?php echo $sAltContactNo ?>">
                    </div>
                    <div class="form-group">
                        <label for="sAddress">Address</label>
                        <input type="text" id="sAddress" name="sAddress" required value="<?php echo $sAddress ?>">
                    </div>

                    <button type="submit" name="S_Update" style="text-align: center; width: 400px" class="add-button">Update</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Delete Modal Structure -->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
        <div class="modal-content">
            <div id="deleteModal" class="admin_monitor_add" style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc;">

                <p id="studentName"></p><br>
                <button id="confirmDelete" class="modalbtn" style="background-color: #ff0000; margin-right:10px;">Delete</button>
                <button id="cancelDelete" class="modalbtn" style="color: black;">Cancel</button>


            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.querySelector('input[name="student_dob"]');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        });

        // ----- Delete modal function--------
        function confirmDelete(studentId, studentName) {
            document.getElementById('studentName').innerText = "Are you sure? Do you want to remove '" + studentName + "' from the Student List?";
            document.getElementById('confirmDelete').setAttribute('data-student-id', studentId);
            document.getElementById('modalOverlay').style.display = 'block';
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var studentId = this.getAttribute('data-student-id');
            window.location.href = '?s_id_del=' + studentId;
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        document.getElementById('modalOverlay').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        // ----- Search function--------
        document.getElementById('searchInput').addEventListener('keyup', function() {
        var searchValue = this.value.toLowerCase();
        var rows = document.querySelectorAll('#studentTableBody tr');

        rows.forEach(function(row) {
            var studentName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            if (studentName.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    </script>
</body>

</html>