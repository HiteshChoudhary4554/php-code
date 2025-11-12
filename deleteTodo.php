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

    require 'Database.php';
    $conn = new Database();
    $db = $conn->connect("localhost", "root", "", "todomanager");

    // Delete the todo item
    $stmt = $db->prepare("DELETE FROM todo WHERE id = ?");
    $stmt->bind_param("i", $todoId);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

header("Location: home.php");
exit();
