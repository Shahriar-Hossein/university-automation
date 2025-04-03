<?php
session_start();
require_once 'dbconnection.php';

if(isset($_SESSION['admin_login_id'])){
    header('Location: http://localhost/sms/admin/dashboard.php');
    exit();
}

if (isset($_POST['ALOGIN'])) {
    $admin_id = $_POST['AID'];
    $admin_password = $_POST['APASSWORD'];

    $ID_check = mysqli_query($conn, "SELECT * FROM `admin_db` WHERE `Id` = '$admin_id'");

    if (mysqli_num_rows($ID_check) > 0) {
        $row = mysqli_fetch_assoc($ID_check);
        // print_r($row);
        if ($row["password"] == $admin_password) {
            $_SESSION['admin_login_id'] = $row['Id'];
            header('Location: admin/dashboard.php');
            exit();
        } else {
            $Password_error = 'Wrong Password...!';
        }
    } else {
        $ID_error = 'Wrong ID...!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="headers">
        <nav>
            <div class="container">
                <div class="navlist">
                    <div class="logo">Student Management</div>
                    <ul>
                        <li><a href="http://localhost/sms/index.php#home">Home</a></li>
                        <li><a href="http://localhost/sms/index.php#about">About</a></li>
                        <li><a href="http://localhost/sms/index.php#features">Notice</a></li>
                        <li><a href="http://localhost/sms/index.php#contact">Contact</a></li>
                    </ul>
                    <div>
                        <a href="loginpanel.php"><button>Login</button></a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="banner">
            <div class="container">
                <h1>Login as Admin</h1>
                <form action="" method="POST">
                    <div class="loginpart" style="margin-top: 50px;">
                        <h3>Enter your ID</h3>
                        <input type="text" required name="AID">
                        <span style="color: red;"><?php if (isset($ID_error)) {
                                                        echo $ID_error;
                                                    } ?></span>
                        <h3>Enter your password</h3>
                        <input type="password" required name="APASSWORD">
                        <span style="color: red; padding-bottom:10px;"><?php if (isset($Password_error)) {
                                                                            echo $Password_error;
                                                                        } ?></span>
                        <div>
                            <button type="submit" name="ALOGIN">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>