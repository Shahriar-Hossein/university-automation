<?php
if (!isset($page_title)) {
    $page_title = 'Admin';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="website icon" type="png" href="../images/weblogo.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../pages/css/adminPagesStyle.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include_once __DIR__ . '/sidebar.php'; ?>
    <script src="../js/script.js"></script>
    <section class="home-section">
        <div class="home-content">
