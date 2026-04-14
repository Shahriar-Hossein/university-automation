<?php
session_start();
require_once __DIR__ . '/../../dbconnection.php';

if(isset($_POST['submitMsg'])){
     $pn_title = $_POST['pubnotice_title'];
     $pn_msg = $_POST['notmsg'];


     $pn_msg_sql = "insert into public_notice_db(pn_title, pn_message) values('$pn_title', '$pn_msg')";

     if(mysqli_query($conn, $pn_msg_sql))
     {
        echo "<script>alert('New message inserted')</script>";
        echo "<script>window.open('addPublicNotice.php','_self')</script>";
     }else{
        echo "<script>alert('Sorry! data has not inserted')</script>";
     }
}


?>
?>
<?php
    $page_title = 'Add Public Notes ||Admin';
    include_once __DIR__ . '/../includes/admin_page_start.php';
?>
<div class="dashboard_header">
    <i class='bx bx-menu'></i>
    <span class="text">Add Public Notice || <span style="font-weight: 300; margin-left: 10px;">Admin</span></span>
</div>
<div class="admin_monitor_add" style="margin: 0 auto;">
        <form class="form" action="" method="POST">
            <h2>Add Public Notice</h2>
            <div class="form-group">
                <label for="pubnotice-title">Public Notice Title</label>
                <input type="text" id="pubnotice-title" name="pubnotice_title" required style="margin: 0 auto;">
            </div>
            <div class="form-group">
                <label for="pubnotice-msg">Public Notice Message</label>
                <textarea name="notmsg" value="" class="notice-msgTxt" required='true'></textarea>
            </div>

            <button style="text-align: center; width: 400px" type="submit" class="add-button" name="submitMsg">Add Public Notice</button>

        </form>
    </div>
<?php include_once __DIR__ . '/../includes/admin_page_end.php'; ?>