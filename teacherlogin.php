<?php
session_start();
require_once 'dbconnection.php';

if (isset($_SESSION['teacher_login_id'])) {
    header('Location: teachers/dashboard.php');
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $teacher_password = $_POST['password'];

    $user = mysqli_query($conn, "SELECT * FROM `teacher_db` WHERE `t_UserName` = '$username'");

    if (mysqli_num_rows($user) > 0) {
        $row = mysqli_fetch_assoc($user);
        // print_r($row);
        if ($row["t_Password"] == $teacher_password) {
            $_SESSION['teacher_login_id'] = $row['t_ID'];
            $_SESSION['teacher_name'] = $row['t_Name'];

            header('Location: teachers/dashboard.php');
            exit();
        } else {
            $Password_error = 'Wrong Password...!';
        }
    } else {
        $user_error = 'No User Found...!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
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
                <h1>Login as Teacher</h1>
                <form action="" method="POST">
                    <div class="loginpart" style="margin-top: 50px;">
                        <h3>Enter your username</h3>
                        <input type="text" required name="username">
                        <span style="color: red;">
                            <?php
                            if (isset($user_error)) {
                                echo $user_error;
                            }
                            ?>
                        </span>
                        <h3>Enter your password</h3>
                        <input type="password" required name="password">
                        <span style="color: red; padding-bottom:10px;">
                            <?php
                            if (isset($Password_error)) {
                                echo $Password_error;
                            }
                            ?>
                        </span>
                        <div>
                            <button type="submit" name="login">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>