<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/login_handler.php';

$base_url = get_base_url();

if (isset($_SESSION['teacher_login_id'])) {
    redirect_to('teachers/dashboard.php');
}

$errors = handle_login([
    'submit_name' => 'login',
    'post_id' => 'username',
    'post_pw' => 'password',
    'table' => 'teacher_db',
    'id_col' => 't_UserName',
    'pw_col' => 't_Password',
    'session_key' => 'teacher_login_id',
    'session_store_col' => 't_ID',
    'session_extra' => ['teacher_name' => 't_Name'],
    'redirect' => 'teachers/dashboard.php',
    'id_not_found_message' => 'No User Found...!'
]);

$user_error = $errors['id'] ?? null;
$Password_error = $errors['password'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
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
                <h1>Login as Teacher</h1>
                <form action="<?php echo $base_url; ?>/teacherlogin.php" method="POST">
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