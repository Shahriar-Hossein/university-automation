<?php
require_once __DIR__ . '/auth.php';

function handle_login(array $config)
{
    $errors = [];

    if (!isset($_POST[$config['submit_name']])) {
        return $errors;
    }

    $id = $_POST[$config['post_id']];
    $pw = $_POST[$config['post_pw']];

    $table = $config['table'];
    $id_col = $config['id_col'];
    $pw_col = $config['pw_col'];

    $escaped_id = mysqli_real_escape_string($GLOBALS['conn'], $id);
    $query = "SELECT * FROM `" . $table . "` WHERE `" . $id_col . "` = '" . $escaped_id . "'";
    $res = mysqli_query($GLOBALS['conn'], $query);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($row[$pw_col] == $pw) {
            $_SESSION[$config['session_key']] = $row[$config['session_store_col'] ?? $id_col];
            if (!empty($config['session_extra']) && is_array($config['session_extra'])) {
                foreach ($config['session_extra'] as $skey => $col) {
                    $_SESSION[$skey] = isset($row[$col]) ? $row[$col] : null;
                }
            }
            redirect_to($config['redirect']);
        } else {
            $errors['password'] = 'Wrong Password...!';
        }
    } else {
        $errors['id'] = $config['id_not_found_message'] ?? 'Wrong ID...!';
    }

    return $errors;
}

?>
