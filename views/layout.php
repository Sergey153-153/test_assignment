<?php
$user = currentUser();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Repair Requests</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <?php if($user): ?>
        <p>Logged in as <?=htmlspecialchars($user['username'])?> (<?=htmlspecialchars($user['role'])?>) | <a href="logout.php">Logout</a></p>
    <?php endif; ?>
    <hr>
</header>
<main>
</main>
</body>
</html>