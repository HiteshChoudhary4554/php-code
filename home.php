<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .container {
            width: 350px;
            margin: 80px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }

        .add-btn {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: #007bff;
            color: #fff;
            font-size: 36px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            margin-top: 40px;
            transition: background 0.2s;
        }

        .add-btn:hover {
            background: #0056b3;
        }

        /* ---- New Section for 3 Buttons ---- */
        .btn-section {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 50px;
        }

        .circle-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .circle-btn img {
            width: 50px;
            height: 50px;
            background: #c5ccd4ff;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .circle-btn img:hover {
            background: #d5d9ddff;
            transform: scale(1.1);
        }

        .circle-btn p {
            margin-top: 8px;
            font-size: 14px;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        // User is not logged in, redirect to login page
        header("Location: login.php");
        exit(); 
    } 
    ?>
    <!-- Home and Logout buttons (HTML + CSS only). Update hrefs if needed -->
    <a href="logout.php" class="logout-btn">Logout</a>
    <div class="container">
        <h2>Welcome to Todo Manager</h2>
        <form action="todo.php" method="get">
            <button type="submit" class="add-btn" title="Add New Todo">+</button>
        </form>
    </div>

    <!-- ✅ New Button Section Added Below -->
    <div class="btn-section">
        <a href="categoryAdd.php" class="circle-btn">
            <img src="categeory.png" alt="Category">
            <p>Category</p>
        </a>

        <a href="#" class="circle-btn">
            <img src="report.png" alt="Report">
            <p>Report</p>
        </a>

        <a href="#" class="circle-btn">
            <img src="search.png" alt="Search">
            <p>Search</p>
        </a>
    </div>
    

    <!-- Todo list (title left, category right). Click opens a details page -->
    <?php
    // DB connection to fetch todos
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'todomanager';
    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if (!$db || $db->connect_error) {
        // don't break the page; just skip listing
    } else {
        // detect category column name in todo table (flexible)
        $todoCatCol = null;
        $colRes = $db->query("SHOW COLUMNS FROM todo");
        if ($colRes) {
            while ($c = $colRes->fetch_assoc()) {
                $field = $c['Field'];
                $norm = preg_replace('/[^a-z0-9]/', '', strtolower($field));
                if (strpos($norm, 'category') !== false || strpos($norm, 'cat') !== false) {
                    $todoCatCol = $field; // use actual column name
                    break;
                }
            }
        }

        // build query using detected column (fallback to no join)
        if ($todoCatCol) {
            $sql = "SELECT t.id, t.title, c.category AS cat_name FROM todo t LEFT JOIN category c ON t.`" . $todoCatCol . "` = c.id ORDER BY t.id DESC";
        } else {
            $sql = "SELECT id, title, NULL AS cat_name FROM todo ORDER BY id DESC";
        }

        $todos = $db->query($sql);
        if ($todos && $todos->num_rows > 0) {
            echo "<div class=\"todo-list-container\" style=\"max-width:600px;margin:30px auto;\">";
            // Heading and column headers
            echo "<h3 style=\"margin:12px 16px 6px 16px;font-size:18px;color:#222;text-align:center\">Todo Stack</h3>";
            echo "<div class=\"todo-headers\" style=\"display:flex;justify-content:space-between;padding:8px 16px;font-weight:700;color:#444;border-bottom:1px solid #eee;\">";
            echo "<span class=\"hdr-title\">Title</span>";
            echo "<span class=\"hdr-cat\">Category Name</span>";
            echo "</div>";
            echo "<ul class=\"todo-list\" style=\"list-style:none;padding:0;margin:0;\">";
            while ($t = $todos->fetch_assoc()) {
                $id = $t['id'];
                $title = htmlspecialchars($t['title']);
                $cat = htmlspecialchars($t['cat_name'] ?? '');
                echo "<li class=\"todo-item\" style=\"padding:12px 16px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;\">";
                echo "<a href=\"viewTodo.php?id={$id}\" class=\"todo-link\" style=\"text-decoration:none;color:inherit;display:flex;justify-content:space-between;width:100%\">";
                echo "<span class=\"todo-title\" style=\"font-weight:600\">{$title}</span>";
                echo "<span class=\"todo-cat\" style=\"color:#666;margin-left:12px\">{$cat}</span>";
                echo "</a>";
                echo "</li>";
            }
            echo "</ul></div>";
        }
        $db->close();
    }
    ?>

    <style>
        /* small styles for the todo list on home page */
        .todo-list-container { background: #fff; padding: 6px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.06); }
        .todo-item:hover { background: #f7f7f8; }
        .todo-title { display:inline-block; }
        .todo-cat { white-space: nowrap; }
    </style>

</body>
</html>
