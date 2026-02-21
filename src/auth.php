<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function login($username, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

// Проверка, что пользователь залогинен
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: ../public/login.php");
        exit;
    }
}

// Проверка роли
function requireRole($role) {
    requireLogin();
    if ($_SESSION['user']['role'] != $role) {
        http_response_code(403);
        die("Доступ запрещён: роль не соответствует");
    }
}

// Получить текущего пользователя
function currentUser() {
    return $_SESSION['user'] ?? null;
}

function panelLink() {
    $user = currentUser();
    if (!$user) return '../public/login.php';
    return $user['role'] === 'dispatcher' ? 'dispatcher_panel.php' : 'master_panel.php';
}