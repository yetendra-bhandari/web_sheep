<?php
session_start();
if (!isset($_SESSION['page_no'])) {
    $_SESSION['page_no'] = 1;
}
require_once 'pdo.php';
if (isset($_COOKIE['remember'])) {
    $stmt = $pdo->prepare('SELECT user_id, name from users WHERE password=:p LIMIT 1');
    $stmt->execute((array(':p' => $_COOKIE['remember'])));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
    }
}
