<?php
session_start();
require_once('../includes/dbconnection.php');

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

// fetch courses
$all_course_data = [];
$all_course_query = mysqli_query($conn, "SELECT * FROM `course_db`");

while ($row = mysqli_fetch_assoc($all_course_query)) {
    $all_course_data[] = $row;
}

// handle delete
if (isset($_GET['c_id_del'])) {
    $c_id_del = base64_decode($_GET['c_id_del']);
    mysqli_query($conn, "DELETE FROM `course_db` WHERE `c_ID` = '$c_id_del'");
    header('Location: manageCourse.php');
    exit();
}

// fill edit form with previous data
$course = null;
if (isset($_GET['edit_id'])) {
    $c_id_edit = base64_decode($_GET['edit_id']);
    $result = mysqli_query($conn, "SELECT * FROM `course_db` WHERE `c_ID`='$c_id_edit'");
    $course = mysqli_fetch_assoc($result);
}

if (isset($_POST['C_Update']) && isset($_GET['edit_id'])) {
    $c_id_edit = base64_decode($_GET['edit_id']);
    $c_Title = $_POST['course_title'];
    $c_Code = $_POST['course_code'];
    $c_Hour = $_POST['course_hour'];

    $s_sql = "UPDATE `course_db` SET c_ID=$c_id_edit, c_title='$c_Title', c_code='$c_Code', c_hours='$c_Hour' WHERE c_ID=$c_id_edit";

    if (mysqli_query($conn, $s_sql)) {
        echo "<script>alert('Course data edited successfully...!')</script>";
        echo "<script>window.open('manageCourse.php','_self')</script>";
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
    <title>Manage Course ||Admin</title>
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
                <span class="text">Manage Course || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
            </div>
            <div class="main_workPanel">
                <div class="main_workPanel_header">
                    <h3>Manage Course</h3>
                </div>
                <div class="admin_monitor_manage">
                    <div class="manage_admin_part">
                        <h2>Manage Course</h2>
                        <div class="table-group">
                            <table>
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Credit Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($all_course_data as $index => $course_data) : ?>
                                        <tr>
                                            <td><?= $index+1; ?></td>
                                            <td><?= $course_data['c_code']; ?></td>
                                            <td><?= $course_data['c_title']; ?></td>
                                            <td><?= $course_data['c_hours']; ?></td>
                                            <td style="display:flex">
                                                <a href="javascript:void(0);" onclick="openModal('<?= base64_encode($course_data['c_ID'] ) ?>')" class="action-view">👁️</a>
                                                <p> / </p>
                                                <a href="javascript:void(0);" onclick="confirmDelete('<?= base64_encode($course_data['c_ID']) ?>', '<?= addslashes($course_data['c_title']); ?>')" class="action-delete"> 🗑️</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

<!-- Modal Structure -->
<div id="popup" class="modal" style="display:<?= isset($course) ? 'block' : 'none'; ?>; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="position:relative; display:flex; justify-content:center; align-items:center; height:100%;">
        <div class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content; position:relative;">
            <span onMouseOver="this.style.background='blue'; this.style.color='white';" onMouseOut="this.style.color='red'; this.style.background='white';" style="position: absolute; top:20px; right:20px; color:red; border-radius:50%; border: 2px solid black; padding-left:9px; padding-right:9px; cursor:pointer;" class="close-button">
                &times;
            </span>
            <form class="form" action="" method="POST">
                <h2 style="text-align: center;">Edit Course</h2>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="course_title">Course Title</label>
                    <input type="text" id="course_title" name="course_title" required style="width: 100%; padding: 10px;" value="<?= $course['c_title'] ?>">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code" required style="width: 100%; padding: 10px;" value="<?= $course['c_code'] ?>">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="course_hour">Credit Hours</label>
                    <select id="course_hour" name="course_hour" required style="width: 100%; padding: 10px;">
                        <option value="" disabled selected>Choose Credit Hours</option>
                        <option value="1" <?= $course['c_hours'] == 1 ? "selected" : "" ?>>1</option>
                        <option value="2" <?= $course['c_hours'] == 2 ? "selected" : "" ?>>2</option>
                        <option value="3" <?= $course['c_hours'] == 3 ? "selected" : "" ?>>3</option>
                        <option value="4" <?= $course['c_hours'] == 4 ? "selected" : "" ?>>4</option>
                        <option value="6" <?= $course['c_hours'] == 6 ? "selected" : "" ?>>6</option>
                    </select>
                </div>
                <button style="text-align: center; width: 400px" type="submit" class="add-button" name="C_Update">Update</button>
            </form>
        </div>
    </div>
</div>


    <!-- Delete Modal Structure -->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
        <div class="modal-content">
            <div id="deleteModal" class="admin_monitor_add" style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc;">
                <p id="courseName"></p><br>
                <button id="confirmDelete" class="modalbtn" style="background-color: #ff0000; margin-right:10px;">Delete</button>
                <button id="cancelDelete" class="modalbtn" style="color: black;">Cancel</button>
            </div>
        </div>
    </div>


    <script src="../js/script.js"></script>
    <script>
        function confirmDelete(courseId, courseName) {
            document.getElementById('courseName').innerText = "Are you sure? Do you want to delete '" + courseName + "' course?";
            document.getElementById('confirmDelete').setAttribute('data-course-id', courseId);
            document.getElementById('modalOverlay').style.display = 'block';
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var courseId = this.getAttribute('data-course-id');
            window.location.href = '?c_id_del=' + courseId;
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        document.getElementById('modalOverlay').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });
    </script>

</body>

</html>