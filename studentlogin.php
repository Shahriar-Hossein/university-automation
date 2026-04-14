<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/login_handler.php';

$base_url = get_base_url();

if (isset($_SESSION['student_login_id'])) {
    redirect_to('students/dashboard.php');
}

$errors = handle_login([
    'submit_name' => 'SLOGIN',
    'post_id' => 'SID',
    'post_pw' => 'SPASSWORD',
    'table' => 'student_db',
    'id_col' => 's_ID',
    'pw_col' => 's_Password',
    'session_key' => 'student_login_id',
    'session_store_col' => 's_ID',
    'redirect' => 'students/dashboard.php',
    'id_not_found_message' => 'Wrong ID...!'
]);

$ID_error = $errors['id'] ?? null;
$Password_error = $errors['password'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/styles.css">
</head>

<body>
    <div class="headers">
        <nav>
            <div class="container">
                <div class="navlist">
                    <div class="logo">Student Management</div>
                    <ul>
                        <li><a href="<?php echo $base_url; ?>/index.php#home">Home</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#about">About</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#features">Notice</a></li>
                        <li><a href="<?php echo $base_url; ?>/index.php#contact">Contact</a></li>
                    </ul>
                    <div>
                        <a href="<?php echo $base_url; ?>/loginpanel.php"><button>Login</button></a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="banner">
            <div class="container">
                <h1>Login as Student</h1>
                <form action="<?php echo $base_url; ?>/studentlogin.php" method="POST">
                    <div class="loginpart" style="margin-top: 50px;">
                        <h3>Enter your ID</h3>
                        <input type="text" required name="SID">
                        <span style="color: red;">
                            <?php
                            if (isset($ID_error)) {
                                echo $ID_error;
                            }
                            ?>
                        </span>
                        <h3>Enter your password</h3>
                        <input type="password" required name="SPASSWORD">
                        <span style="color: red; padding-bottom:10px;">
                            <?php
                                if (isset($Password_error)) {
                                    echo $Password_error;
                                }
                            ?>
                        </span>
                        <div>
                            <button type="submit" name="SLOGIN">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>