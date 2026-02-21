<?php
require_once '../src/auth.php';
require_once '../src/models/Request.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$user = currentUser();

// Если не авторизован → login
if (!$user) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
        if (login($_POST['username'], $_POST['password'])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    }
    include 'login.php';
    exit;
}

// POST-запросы через action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            RequestModel::create($_POST);
            header("Location: index.php");
            exit;

        case 'assign':
            RequestModel::updateStatus($_POST['requestId'], 'assigned', $_POST['masterId']);
            header("Location: index.php");
            exit;

        case 'cancel':
            RequestModel::updateStatus($_POST['requestId'], 'canceled');
            header("Location: index.php");
            exit;

        case 'take_in_progress':
            $res = RequestModel::takeInProgress($_POST['requestId'], $_POST['masterId']);

            if ($res['success']) {
                // Успешно — просто редирект на панель
                header("Location: index.php");
                exit;
            } else {
                // Ошибка → сохраняем в сессию и редирект
                $_SESSION['error'] = $res['message'];
                header("Location: index.php");
                exit;
            }

        case 'done':
            RequestModel::updateStatus($_POST['requestId'], 'done');
            header("Location: index.php");
            exit;
    }
}

// GET-запрос → панель по роли
if ($user['role'] === 'dispatcher') include 'dispatcher_panel.php';
else include 'master_panel.php';