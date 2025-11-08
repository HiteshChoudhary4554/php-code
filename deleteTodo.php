<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Accept either POST (form) or GET (link) to delete
$todoId = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['todo_id'])) {
    $todoId = intval($_POST['todo_id']);
} elseif (isset($_GET['id'])) {
    $todoId = intval($_GET['id']);
}

if ($todoId > 0) {

    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'todomanager';

    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if (!$db || $db->connect_error) {
        header("Location: home.php?error=db");
        exit();
    }

    // Delete the todo item
    $stmt = $db->prepare("DELETE FROM todo WHERE id = ?");
    $stmt->bind_param("i", $todoId);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

header("Location: home.php");
exit();
