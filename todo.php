<!DOCTYPE html>
<html>

<head>
    <title>Add Todo</title>
    <link rel="stylesheet" href="/php-code/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .container {
            width: 370px;
            margin: 80px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        input[type="text"],
        textarea,
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .select-with-btn {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .select-with-btn select {
            flex: 1;
        }

        .add-category-btn {
            width: 80px;
            display: inline-block;
            padding: 9px 12px;
            background: #28a745;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .add-category-btn:hover {
            background: #218838;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        // User is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todomanager";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch categories from database
    $categoryQuery = "SELECT * FROM category ORDER BY category";
    $categories = $conn->query($categoryQuery);

    // Load draft from session if present (for prefilling after returning from add-category)
    $draft = isset($_SESSION['todo_draft']) ? $_SESSION['todo_draft'] : null;

    // If an id is provided via GET, load existing todo for editing
    $isEdit = false;
    $editId = 0;
    if (isset($_GET['id']) && intval($_GET['id']) > 0) {
        $editId = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM todo WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $editId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            // populate draft values from db if not coming from session draft
            $draft = [
                'title' => $row['title'] ?? '',
                'description' => $row['description'] ?? '',
                'due_date' => $row['duedate'] ?? '',
                'category' => $row['category'] ?? ($row['category_id'] ?? '')
            ];
            $isEdit = true;
        }
        $stmt->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = isset($_POST['action']) ? $_POST['action'] : 'save_todo';

        // If user clicked Add (to go to categoryAdd.php), save draft and redirect
        if ($action === 'add_category') {
            // Save current form fields into session draft
            $_SESSION['todo_draft'] = [
                'title' => isset($_POST['title']) ? $_POST['title'] : '',
                'description' => isset($_POST['description']) ? $_POST['description'] : '',
                'due_date' => isset($_POST['due_date']) ? $_POST['due_date'] : '',
                'category' => isset($_POST['category']) ? $_POST['category'] : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'pending'
            ];
            // Redirect to category add page and indicate coming from todo so categoryAdd can show a Back button
            header('Location: categoryAdd.php?from=todo');
            exit();
        }

        // Otherwise proceed to save the todo (save_todo)
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $due_date = isset($_POST['due_date']) && $_POST['due_date'] !== '' ? $_POST['due_date'] : null;
        $category_id = isset($_POST['category']) ? (int) $_POST['category'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'pending';
        $isUpdate = isset($_POST['todo_id']) && intval($_POST['todo_id']) > 0;
        $updateId = $isUpdate ? intval($_POST['todo_id']) : 0;

        // Basic validation
        if ($title === '' || $description === '' || $category_id === 0) {
            echo "<p style='color:red; text-align:center;'>Please fill title, description and select a category.</p>";
        } else {
            // Detect correct category column name in todo table.
            $catCol = null;
            $colRes = $conn->query("SHOW COLUMNS FROM todo");
            if ($colRes) {
                while ($c = $colRes->fetch_assoc()) {
                    $field = $c['Field'];
                    $norm = preg_replace('/[^a-z0-9]/', '', strtolower($field));
                    if (strpos($norm, 'category') !== false || strpos($norm, 'cat') !== false) {
                        $catCol = $field;
                        break;
                    }
                }
            }

            if (!$catCol) {
                echo "<p style='color:red; text-align:center;'>Todo table does not have a category column (expected 'category_id' or 'category'). Please update your schema.</p>";
            } else {
                if ($isUpdate) {
                    // Update existing todo (include status)
                    $updateSql = "UPDATE todo SET title = ?, description = ?, duedate = ?, `" . $catCol . "` = ?, status = ? WHERE id = ?";
                    $ustmt = $conn->prepare($updateSql);
                    if ($ustmt) {
                        $ustmt->bind_param('sssisi', $title, $description, $due_date, $category_id, $status, $updateId);
                        if ($ustmt->execute()) {
                            if (isset($_SESSION['todo_draft'])) {
                                unset($_SESSION['todo_draft']);
                            }
                            header("Location: home.php");
                            exit();
                        } else {
                            echo "<p style='color:red; text-align:center;'>Update failed: " . htmlspecialchars($ustmt->error) . "</p>";
                        }
                        $ustmt->close();
                    } else {
                        echo "<p style='color:red; text-align:center;'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>";
                    }
                } else {
                    // Build dynamic insert SQL using the detected category column and status
                    $insertSql = "INSERT INTO todo (title, description, duedate, `" . $catCol . "`, status) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertSql);
                    if ($stmt) {
                        $stmt->bind_param('sssis', $title, $description, $due_date, $category_id, $status);
                        if ($stmt->execute()) {
                            // Clear draft on successful save
                            if (isset($_SESSION['todo_draft'])) {
                                unset($_SESSION['todo_draft']);
                            }
                            header("Location: home.php");
                            exit();
                        } else {
                            echo "<p style='color:red; text-align:center;'>Insert failed: " . htmlspecialchars($stmt->error) . "</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p style='color:red; text-align:center;'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>";
                    }
                }
            }
        }
    }
    ?>
    <a href="home.php" class="homeUIX">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>
    <div class="container">
        <h2><?php echo $isEdit ? 'Edit Todo' : 'Add Todo'; ?></h2>
        <form method="post" action="">
            <?php if ($isEdit) { ?>
                <input type="hidden" name="todo_id" value="<?php echo $editId; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="title">Todo Title</label>
                <input type="text" id="title" name="title" placeholder="Enter title" required value="<?php echo isset(
                                                                                                            $draft['title']
                                                                                                        ) ? htmlspecialchars($draft['title']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description">Task Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the task" required><?php echo isset($draft['description']) ? htmlspecialchars($draft['description']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="<?php echo isset($draft['due_date']) ? htmlspecialchars($draft['due_date']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <div class="select-with-btn">
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select category</option>
                        <?php
                        if ($categories->num_rows > 0) {
                            while ($category = $categories->fetch_assoc()) {
                                $sel = '';
                                if (isset($draft['category']) && (string)$draft['category'] === (string)$category['id']) {
                                    $sel = ' selected';
                                }
                                echo "<option value='" . $category['id'] . "'" . $sel . ">" . htmlspecialchars($category['category']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <button type="submit" name="action" value="add_category" formnovalidate class="add-category-btn" title="Add Category">Add</button>
                </div>
            </div>
            <button type="submit" name="action" value="save_todo"><?php echo $isEdit ? 'Update Todo' : 'Add Todo'; ?></button>
        </form>
    </div>
</body>

</html>