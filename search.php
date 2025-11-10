<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$database = "todomanager";
$conn = new mysqli($host, $user, $pass, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = "";
$result = [];

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);

    // Search only by title
    $sql = "SELECT id, title, duedate, status 
            FROM todo
            WHERE title LIKE '%$search%'
            ORDER BY duedate ASC";

    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Todos by Title</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6fa;
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 750px;
            margin: 0 auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px 15px;
            border: 1px solid #bbb;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        a.todo-link {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a.todo-link:hover {
            text-decoration: underline;
        }

        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }

        .status-done {
            color: #2ecc71;
            font-weight: bold;
        }

        .no-result {
            text-align: center;
            color: #777;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <link rel="stylesheet" href="style.css">
    <a href="home.php" class="homeUI-btn">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>

    <div class="container">
        <h2>🔍 Search Todo by Title</h2>

        <form method="GET">
            <input type="text" name="query" placeholder="Enter todo title..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <?php if (isset($_GET['query'])): ?>
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['duedate']) ?></td>
                            <td>
                                <a class="todo-link" href="viewTodo.php?id=<?= $row['id'] ?>">
                                    <?= htmlspecialchars($row['title']) ?>
                                </a>
                            </td>
                            <td class="<?= ($row['status'] == 'done') ? 'status-done' : 'status-pending' ?>">
                                <?= $row['status'] == 'done' ? '✅ Done' : '⏳ Pending' ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="no-result">No todos found with that title.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>