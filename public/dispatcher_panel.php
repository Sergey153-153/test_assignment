<?php
require_once '../src/auth.php';
require_once '../src/models/Request.php';
requireRole('dispatcher');
$user = currentUser();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dispatcher Panel</title>
</head>
<body>
<h2>Dispatcher Panel</h2>
<p><a href="<?=panelLink()?>">Главная / Панель</a></p>
<hr>
<p>Привет, <?=htmlspecialchars($user['username'])?></p>
<a href="create_request.php">Создать новую заявку</a> | 
<a href="logout.php">Выйти</a>
<hr>
<h3>Список заявок</h3>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Клиент</th>
    <th>Телефон</th>
    <th>Адрес</th>
    <th>Описание</th>
    <th>Статус</th>
    <th>Назначен на</th>
    <th>Действия</th>
</tr>
<?php foreach(RequestModel::allWithMaster() as $r): ?>
<tr>
    <td><?=$r['id']?></td>
    <td><?=$r['clientName']?></td>
    <td><?=$r['phone']?></td>
    <td><?=$r['address']?></td>
    <td><?=$r['problemText']?></td>
    <td><?=$r['status']?></td>
    <td><?=$r['masterName'] ?? '-'?></td>
    <td>
        <?php if($r['status'] === 'new'): ?>
            <!-- Назначить и Отменить -->
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="assign">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <select name="masterId">
                    <option value="2">master1</option>
                    <option value="3">master2</option>
                </select>
                <button type="submit">Назначить</button>
            </form>
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <button type="submit">Отменить</button>
            </form>

        <?php elseif($r['status'] === 'assigned'): ?>
            <!-- Только отменить -->
            <form method="POST" action="index.php" style="display:inline;">
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" name="requestId" value="<?=$r['id']?>">
                <button type="submit">Отменить</button>
            </form>

        <?php else: ?>
            <!-- Для in_progress, done, canceled -->
            -
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>