<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['todo_id'])) {
    $todoId = intval($_POST['todo_id']);

    require 'Database.php';
    $db = new Database();
    $conn = $db->connect("localhost", "root", "", "todomanager");

    // Update the status to 'done'
    $stmt = $conn->prepare("UPDATE todo SET status = 'done' WHERE id = ?");
    $stmt->bind_param("i", $todoId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Redirect back to home page
header("Location: home.php");
exit();
