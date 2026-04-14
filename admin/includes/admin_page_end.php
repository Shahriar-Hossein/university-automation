    </div>
    </section>
    <?php
    if (!isset($base_url)) {
        if (function_exists('get_base_url')) {
            $base_url = get_base_url();
        } else {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
            $raw_base_path = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
            $base_path = preg_replace('#/admin$#', '', $raw_base_path);
            $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . ($base_path === '' || $base_path === '/' ? '' : $base_path);
        }
    }
    ?>
        <script src="<?php echo $base_url; ?>/admin/js/script.js"></script>
    </body>

    </html>
