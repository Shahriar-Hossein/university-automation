<?php
// Expected variables before include:
// $form_action, $id_name, $pw_name, $id_label, $submit_name
// $id_error, $password_error
if (!isset($form_action)) {
    $form_action = $_SERVER['PHP_SELF'];
}
if (!isset($id_name)) {
    $id_name = 'username';
}
if (!isset($pw_name)) {
    $pw_name = 'password';
}
if (!isset($id_label)) {
    $id_label = 'Enter your username';
}
if (!isset($submit_name)) {
    $submit_name = 'login';
}
?>
<form action="<?php echo $form_action; ?>" method="POST">
    <div class="loginpart" style="margin-top: 50px;">
        <h3><?php echo htmlspecialchars($id_label); ?></h3>
        <input type="text" required name="<?php echo htmlspecialchars($id_name); ?>">
        <span style="color: red;">
            <?php if (!empty($id_error)) { echo $id_error; } ?>
        </span>
        <h3>Enter your password</h3>
        <input type="password" required name="<?php echo htmlspecialchars($pw_name); ?>">
        <span style="color: red; padding-bottom:10px;">
            <?php if (!empty($password_error)) { echo $password_error; } ?>
        </span>
        <div>
            <button type="submit" name="<?php echo htmlspecialchars($submit_name); ?>">Login</button>
        </div>
    </div>
</form>
