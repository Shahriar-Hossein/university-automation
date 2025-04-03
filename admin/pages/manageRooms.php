<?php
session_start();
require_once('../includes/dbconnection.php');

if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}

// fetch rooms
$rooms = [];
$rooms_query = mysqli_query($conn, "SELECT * FROM rooms");

while ($row = mysqli_fetch_assoc($rooms_query)) {
    $rooms[] = $row;
}

// handle delete
if (isset($_GET['r_id_del'])) {
    $r_id_del = base64_decode($_GET['r_id_del']);
    mysqli_query($conn, "DELETE FROM `rooms` WHERE `id` = '$r_id_del'");
    header('Location: manageRooms.php');
    exit();
}

// fill edit form with previous data
$room = null;

if (isset($_GET['edit_id'])) {
    $r_id_edit = base64_decode($_GET['edit_id']);
    $result = mysqli_query($conn, "SELECT * FROM rooms WHERE id=$r_id_edit");
    $room = mysqli_fetch_assoc($result);
}

if (isset($_POST['Room_Update']) && isset($_GET['edit_id'])) {
    $r_id_edit = base64_decode($_GET['edit_id']);
    $room_no = $_POST['room_no'];
    $name = $_POST['room_name'];
    $description = $_POST['room_description'];

    // Check if the room number already exists for a different room
    $check_room_sql = "SELECT * FROM rooms WHERE room_no = '$room_no' AND id != $r_id_edit";
    $result = mysqli_query($conn, $check_room_sql);

    if (mysqli_num_rows($result) > 0) {
        // Room number already exists for another room
        echo "<script>alert('Room number already exists! Please choose a different number.')</script>";
    } else {
        // Room number is unique, proceed with update
        $s_sql = "UPDATE `rooms` SET room_no='$room_no', name='$name', description='$description' where id=$r_id_edit";

        if (mysqli_query($conn, $s_sql)) {
            echo "<script>alert('Room data edited successfully...!')</script>";
            echo "<script>window.open('manageRooms.php','_self')</script>";
        } else {
            echo "<script>alert('Sorry! data has not updated...!')</script>";
        }
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
            <span class="text">Manage Rooms || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
        </div>
        <div class="main_workPanel">
            <div class="main_workPanel_header">
                <h3>Manage Rooms</h3>
            </div>
            <div class="admin_monitor_manage">
                <div class="manage_admin_part">
                    <h2>All Rooms</h2>
                    <div class="table-group">
                        <table>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Room No</th>
                                    <th>Room Name</th>
                                    <th>Room Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($rooms as $index => $room_data) :    ?>
                                <tr>
                                    <td><?= $index+1; ?></td>
                                    <td><?= $room_data['room_no']; ?></td>
                                    <td><?= $room_data['name']; ?></td>
                                    <td><?= $room_data['description']; ?></td>
                                    <td style="display:flex">
                                        <a href="javascript:void(0);" onclick="openModal('<?= base64_encode($room_data['id'] ) ?>')" class="action-view">👁️</a>
                                        <p> / </p>
                                        <a href="javascript:void(0);" onclick="confirmDelete('<?= base64_encode($room_data['id']) ?>', '<?= addslashes($room_data['room_no']); ?>')" class="action-delete"> 🗑️</a>
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
<div id="popup" class="modal" style="display:<?= isset($room) ? 'block' : 'none'; ?>; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="position:relative; display:flex; justify-content:center; align-items:center; height:100%;">
        <div class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content; position:relative;">
            <span onMouseOver="this.style.background='blue'; this.style.color='white';" onMouseOut="this.style.color='red'; this.style.background='white';" style="position: absolute; top:20px; right:20px; color:red; border-radius:50%; border: 2px solid black; padding-left:9px; padding-right:9px; cursor:pointer;" class="close-button">
                &times;
            </span>
            <form class="form" action="" method="POST">
                <h2>Edit Room</h2>
                <div class="form-group">
                    <label for="room_no">Room Number</label>
                    <input type="text" id="room_no" name="room_no" required style="margin: 0 auto;" value="<?= $room['room_no'] ?>">
                </div>

                <div class="form-group">
                    <label for="room_name">Room Name</label>
                    <input type="text" id="room_name" name="room_name" required value="<?= $room['name'] ?>">
                </div>
                
                <div class="form-group">
                    <label for="room_description">Room Description</label>
                    <input type="text" id="room_description" name="room_description" value="<?= $room['description'] ?>">
                </div>
                <button style="text-align: center; width: 400px" type="submit" class="add-button" name="Room_Update">Update</button>
            </form>
        </div>
    </div>
</div>


<!-- Delete Modal Structure -->
<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="position:relative; display:flex; justify-content:center; align-items:center; height:100%;">
        <div id="deleteModal" class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content;">
            <p id="roomNo" style="margin-bottom:20px;"></p>
            <div style="display:flex; justify-content:flex-end;">
                <button id="confirmDelete" class="modalbtn" style="background-color: #ff0000; color: white; padding:10px 20px; border:none; border-radius:4px; margin-right:10px;">Delete</button>
                <button id="cancelDelete" class="modalbtn" style="background-color: #ccc; color: black; padding:10px 20px; border:none; border-radius:4px;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/script.js"></script>
<script>
    const confirmDelete = (roomId, roomNo) => {
        document.getElementById('roomNo').innerText = "Are you sure you want to delete room no. " + roomNo + "?";
        document.getElementById('confirmDelete').setAttribute('data-room-id', roomId);
        document.getElementById('modalOverlay').style.display = 'block';
    };

    document.getElementById('confirmDelete').addEventListener('click', function() {
        var roomId = this.getAttribute('data-room-id');
        window.location.href = '?r_id_del=' + roomId;
    });

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('modalOverlay').style.display = 'none';
        window.open('manageRooms.php','_self');
    });

    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            document.getElementById('modalOverlay').style.display = 'none';
        }
    });
</script>

</body>

</html>