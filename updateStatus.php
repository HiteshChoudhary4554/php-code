<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['todo_id'])) {
    $todoId = intval($_POST['todo_id']);

    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'todomanager';

    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if (!$db || $db->connect_error) {
        header("Location: home.php?error=db");
        exit();
    }

    // Update the status to 'done'
    $stmt = $db->prepare("UPDATE todo SET status = 'done' WHERE id = ?");
    $stmt->bind_param("i", $todoId);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

// Redirect back to home page
header("Location: home.php");
exit();
