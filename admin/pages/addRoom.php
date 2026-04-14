<?php
session_start();
require_once '../includes/dbconnection.php';

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: ../adminlogin.php');
}
$admin_login_user = $_SESSION['admin_login_id'];

if (isset($_POST['addRoomButton'])) {
    $room_no = $_POST['room_no'];
    $room_name = $_POST['room_name'];
    $room_description = $_POST['room_description'];

    // Check if the room number already exists
    $check_room_sql = "SELECT * FROM rooms WHERE room_no = '$room_no'";
    $result = mysqli_query($conn, $check_room_sql);

    if (mysqli_num_rows($result) > 0) {
        // Room number already exists
        echo "<script>alert('Room number already exists! Please choose a different number.')</script>";
    } else {
        // Room number is unique, insert new room
        $room_sql = "INSERT INTO rooms(room_no, name, description) VALUES('$room_no', '$room_name', '$room_description')";
        
        if (mysqli_query($conn, $room_sql)) {
            echo "<script>alert('New room added successfully')</script>";
            echo "<script>window.open('addRoom.php','_self')</script>";
        } else {
            echo "<script>alert('Sorry! Error occurred while creating room')</script>";
        }
    }
}
?>
?>
<?php
    $page_title = 'Add Room ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Add Room || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
    <div class="main_workPanel_header">
        <h3>Add Room</h3>
    </div>
    <div class="admin_monitor_add" style="margin: 0 auto;">
        <form class="form" action="" method="POST">
            <h2>Add Room</h2>
            <div class="form-group">
                <label for="room_no">Room No</label>
                <input type="text" id="room_no" name="room_no" required style="margin: 0 auto;">
            </div>
            <div class="form-group">
                <label for="room_name">Room Name</label>
                <input type="text" id="room_name" name="room_name" >
            </div>
            <div class="form-group">
                <label for="room_description">Room Description</label>
                <input type="text" id="room_description" name="room_description" >
            </div>

            <button style="text-align: center; width: 400px" type="submit" class="add-button" name="addRoomButton">Add Room</button>
        </form>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>