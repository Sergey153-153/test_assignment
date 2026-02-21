<?php
require_once '../src/auth.php';
require_once '../src/models/Request.php';
requireRole('master');
$user = currentUser();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Master Panel</title>
</head>
<body>
<h2>Master Panel</h2>
<p>
    <a href="<?=panelLink()?>">Главная / Панель</a> | 
    <a href="logout.php">Выйти</a>
</p>
<hr>
<p>Привет, <?=htmlspecialchars($user['username'])?> | <a href="logout.php">Выйти</a></p>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['error'])) {
    echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}
?>
<hr>
<h3>Список ваших заявок</h3>
<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Клиент</th>
    <th>Телефон</th>
    <th>Адрес</th>
    <th>Описание</th>
    <th>Статус</th>
    <th>Действия</th>
</tr>
<?php foreach(RequestModel::all() as $r): 
    if($r['assignedTo'] != $user['id']) continue;
?>
<tr>
    <td><?=$r['id']?></td>
    <td><?=$r['clientName']?></td>
    <td><?=$r['phone']?></td>
    <td><?=$r['address']?></td>
    <td><?=$r['problemText']?></td>
    <td><?=$r['status']?></td>
    <td>
        <?php if($r['status'] === 'assigned'): ?>
            <!-- Мастер может взять в работу -->
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="take_in_progress">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <input type="hidden" name="masterId" value="<?=currentUser()['id']?>">
                <button type="submit">Взять в работу</button>
            </form>

        <?php elseif($r['status'] === 'in_progress'): ?>
            <!-- Мастер может завершить или отменить -->
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="done">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <button type="submit">Завершить</button>
            </form>
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <button type="submit">Отменить</button>
            </form>

        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>