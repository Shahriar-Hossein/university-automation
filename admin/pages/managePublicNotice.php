<?php
session_start();
require_once('../includes/dbconnection.php');
if (!isset($_SESSION['admin_login_id'])) {
    header('Location: http://localhost/sms/adminlogin.php');
}


if (isset($_GET['pn_id_del'])) {
    $pn_id_del = base64_decode($_GET['pn_id_del']);
    mysqli_query($conn, "DELETE FROM `public_notice_db` WHERE `pn_id` = '$pn_id_del'");
    header('Location: managePublicNotice.php');
    exit();
}

$public_notice = null;
if (isset($_GET['edit_id'])) {
    $pn_id_edit = base64_decode($_GET['edit_id']);
    $pn_get_sql = "Select * from `public_notice_db` where `pn_id`='$pn_id_edit'";

    $get_result = mysqli_query($conn, $pn_get_sql);
    $public_notice = mysqli_fetch_assoc($get_result);

    // $pn_TITLE = $row['pn_title'];
    // $pn_MESSAGE = $row['pn_message'];
}

if (isset($_POST['PN_Update']) && $public_notice) {
    $pn_Title = $_POST['pubnotice_title'];
    $pn_Message = $_POST['notmsg'];

    $pn_sql = "update `public_notice_db` set pn_title='$pn_Title', pn_message='$pn_Message' where pn_id=$pn_id_edit";

    if (mysqli_query($conn, $pn_sql)) {
        echo "<script>alert('Message edited successfully...!')</script>";
        echo "<script>window.open('managePublicNotice.php','_self')</script>";
    } else {
        echo "<script>alert('Sorry! data has not updated...!')</script>";
    }
}


?>
?>
<?php
    $page_title = 'Manage Public Notice ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Manage Public Notice || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="main_workPanel">
    <div>
        <h3>Manage Public Notice</h3>
    </div>
    <div class="admin_monitor">
        <div class="manage_admin_part">
            <h2>Manage Public Notice</h2>
            <div class="table-group">
                <table>
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Notice Date</th>
                            <th>Notice Title</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $all_publicNotice = mysqli_query($conn, "SELECT * FROM `public_notice_db`");
                        $serial_no = 1;
                        while ($row = mysqli_fetch_assoc($all_publicNotice)) {
                        ?>

                            <tr>
                                <td><?php echo $serial_no++; ?></td>
                                <td><?php echo  date('d-M-Y', strtotime($row['pn_date'])); ?></td>
                                <td><?php echo $row['pn_title']; ?></td>
                                <td><?php echo $row['pn_message']; ?></td>

                                <td style="display:flex">
                                    <a href="javascript:void(0);" onclick="openModal('<?= base64_encode($row['pn_id'])?>')" class="action-view">👁️</a>
                                    <p> / </p>
                                    <a href="javascript:void(0);" onclick="confirmDelete('<?= base64_encode($row['pn_id']); ?>', '<?= addslashes($row['pn_title']); ?>')" class="action-delete"> 🗑️</a>
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


    <!-- Modal Structure -->

    <div id="popup" class="modal" style="display:<?= isset($public_notice) ? 'block' : 'none'; ?>; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
    <div class="modal-content" style="position:relative; display:flex; justify-content:center; align-items:center; height:100%;">
        <div class="admin_monitor_add" style="background-color: white; padding:20px; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: fit-content; position:relative;">
            <span onMouseOver="this.style.background='blue'; this.style.color='white';" onMouseOut="this.style.color='red'; this.style.background='white';" style="position: absolute; top:20px; right:20px; color:red; border-radius:50%; border: 2px solid black; padding-left:9px; padding-right:9px; cursor:pointer;" class="close-button">
                &times;
            </span>

                <form class="form" action="" method="POST">
                    <h4>Edit Public Notice</h4>
                    <div class="form-group">
                        <label for="pubnotice-title">Public Notice Title</label>
                        <input type="text" id="pubnotice-title" name="pubnotice_title" required style="margin: 0 auto;" value="<?= $public_notice['pn_title'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="pubnotice-msg">Public Notice Message</label>
                        <textarea name="notmsg" value="" class="notice-msgTxt" required='true'><?= $public_notice['pn_message'] ?></textarea>
                    </div>

                    <button style="text-align: center; width: 400px" type="submit" class="add-button" name="PN_Update">Update</button>

                </form>

            </div>
        </div>
    </div>

    <!-- Delete Modal Structure -->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.5); z-index:999;">
        <div class="modal-content">
            <div id="deleteModal" class="admin_monitor_add" style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); border:1px solid #ccc;">

                <p id="publicNoticeTitle"></p><br>
                <button id="confirmDelete" class="modalbtn" style="background-color: #ff0000; margin-right:10px;">Delete</button>
                <button id="cancelDelete" class="modalbtn" style="color: black;">Cancel</button>


            </div>
        </div>
    </div>


    <script src="../js/script.js"></script>
    <script>
        // ----- Delete modal function--------
        function confirmDelete(publicNoticeId, publicNoticeTitle) {
            document.getElementById('publicNoticeTitle').innerText = "Are you sure? Do you want to remove '" + publicNoticeTitle + "' from the Public Notice List?";
            document.getElementById('confirmDelete').setAttribute('data-pn-id', publicNoticeId);
            document.getElementById('modalOverlay').style.display = 'block';
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            var publicNoticeId = this.getAttribute('data-pn-id');
            window.location.href = '?pn_id_del=' + publicNoticeId;
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        document.getElementById('modalOverlay').addEventListener('click', function() {
            document.getElementById('modalOverlay').style.display = 'none';
        });
    </script>

<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>