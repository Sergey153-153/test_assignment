<?php
require_once 'config.php';

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB connection error: " . $e->getMessage());
}

// Создание таблиц
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('dispatcher', 'master'))
)");

$db->exec("CREATE TABLE IF NOT EXISTS requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    clientName TEXT NOT NULL,
    phone TEXT NOT NULL,
    address TEXT NOT NULL,
    problemText TEXT NOT NULL,
    status TEXT NOT NULL CHECK(status IN ('new','assigned','in_progress','done','canceled')) DEFAULT 'new',
    assignedTo INTEGER,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(assignedTo) REFERENCES users(id)
)");

$stmt = $db->query("SELECT COUNT(*) as cnt FROM users");
if($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] == 0) {
    $db->exec("INSERT INTO users (username, password, role) VALUES
        ('dispatcher1', '".password_hash("1234", PASSWORD_DEFAULT)."', 'dispatcher'),
        ('master1', '".password_hash("1234", PASSWORD_DEFAULT)."', 'master'),
        ('master2', '".password_hash("1234", PASSWORD_DEFAULT)."', 'master')
    ");
}