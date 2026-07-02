<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function ensureUsersTable()
{
    $db = getDbConnection();
    $db->query(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL UNIQUE,
            phone VARCHAR(40) DEFAULT '',
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

function signInUser(array $user)
{
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_name'] = $user['fullname'];
    $_SESSION['user_email'] = $user['email'];
}

function requireLogin()
{
    if (empty($_SESSION['logged_in'])) {
        header('Location: create.php');
        exit;
    }
}

function handleLogout()
{
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header('Location: create.php');
        exit;
    }
}

function currentUserName()
{
    return $_SESSION['user_name'] ?? 'Guest';
}

function currentUserInitial()
{
    $name = trim(currentUserName());
    return strtoupper(substr($name !== '' ? $name : 'G', 0, 1));
}

