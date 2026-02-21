<?php
require_once '../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (login($username, $password)) {
        $role = currentUser()['role'];
        if ($role === 'dispatcher') header('Location: dispatcher_panel.php');
        else header('Location: master_panel.php');
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<p>Тестовые пользователи: <br>
dispatcher1 / 1234 <br>
master1 / 1234 <br>
master2 / 1234</p>
</body>
</html>