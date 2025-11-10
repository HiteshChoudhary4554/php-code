<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$todoId = intval($_GET['id']);

// Database connection
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'todomanager';

$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Detect category column name
$todoCatCol = null;
$colRes = $db->query("SHOW COLUMNS FROM todo");
if ($colRes) {
    while ($c = $colRes->fetch_assoc()) {
        $field = $c['Field'];
        $norm = preg_replace('/[^a-z0-9]/', '', strtolower($field));
        if (strpos($norm, 'category') !== false || strpos($norm, 'cat') !== false) {
            $todoCatCol = $field;
            break;
        }
    }
}

// Fetch todo details with category name
if ($todoCatCol) {
    $sql = "SELECT t.*, c.category as category_name 
            FROM todo t 
            LEFT JOIN category c ON t.`" . $todoCatCol . "` = c.id 
            WHERE t.id = ?";
} else {
    $sql = "SELECT *, NULL as category_name FROM todo WHERE id = ?";
}

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $todoId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: home.php");
    exit();
}

$todo = $result->fetch_assoc();
$db->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Todo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .todo-detail-container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .todo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .todo-due-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-family: monospace;
        }

        .todo-title {
            font-size: 24px;
            color: #333;
            margin: 0;
        }

        .todo-category {
            background: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 14px;
        }

        .todo-content {
            margin: 20px 0;
            line-height: 1.6;
            color: #555;
        }

        .todo-meta {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .meta-item {
            font-size: 14px;
            color: #666;
        }

        .meta-item strong {
            color: #333;
            margin-right: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            justify-content: flex-end;
        }

        .edit-btn,
        .delete-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .edit-btn {
            background: #28a745;
            color: white;
        }

        .edit-btn:hover {
            background: #218838;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>

<body>
    <a href="home.php" class="homeUI-btn">Back</a>
    <a href="logout.php" class="logout-btn">Logout</a>

    <div class="todo-detail-container">
        <div class="todo-header">
            <div>
                <div class="todo-due-date">Due: <?php
                $duedate = $todo['duedate'] ?? null;
                echo $duedate ? date('Y-m-d', strtotime($duedate)) : 'No date set';
                ?></div>
                <h1 class="todo-title"><?php echo htmlspecialchars($todo['title']); ?></h1>
            </div>
            <span class="todo-category"><?php echo htmlspecialchars($todo['category_name'] ?? 'No Category'); ?></span>
        </div>

        <div class="todo-content">
            <?php echo nl2br(htmlspecialchars($todo['description'] ?? 'No description available.')); ?>
        </div>

        <div class="todo-meta">
            <span class="meta-item">
                <strong>Status:</strong>
                <?php echo htmlspecialchars($todo['status'] ?? 'pending'); ?>
            </span>
            <span class="meta-item">
                <strong>Priority:</strong>
                <?php echo htmlspecialchars($todo['priority'] ?? 'Not set'); ?>
            </span>
        </div>

        <div class="action-buttons">
            <button onclick="location.href='todo.php?id=<?php echo $todoId; ?>'" class="edit-btn">Edit</button>
            <button
                onclick="if(confirm('Are you sure you want to delete this todo?')) location.href='deleteTodo.php?id=<?php echo $todoId; ?>'"
                class="delete-btn">Delete</button>
        </div>
    </div>
</body>

</html>