<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        /* Search Bar Styles */
        .search-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 0 20px;
            box-sizing: border-box;
            position: relative;
        }

        .search-box {
            width: 100%;
            position: relative;
        }

        #searchInput {
            width: 100%;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        #searchInput:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 2px 12px rgba(0, 123, 255, 0.15);
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .suggestion-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: #f5f7ff;
        }

        .suggestion-title {
            color: #333;
            font-weight: 500;
        }

        .suggestion-category {
            color: #666;
            font-size: 0.9em;
            padding-left: 15px;
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

        <a href="report.php" class="circle-btn">
            <img src="report.png" alt="Report">
            <p>Report</p>
        </a>

        <a href="search.php" class="circle-btn">
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
            // Put completed items (status = 'done') after pending ones, then sort by due date (NULLs last)
            $sql = "SELECT t.id, t.title, t.duedate, t.status, c.category AS cat_name FROM todo t LEFT JOIN category c ON t.`" . $todoCatCol . "` = c.id ORDER BY CASE WHEN t.status = 'done' THEN 1 ELSE 0 END, CASE WHEN t.duedate IS NULL THEN 1 ELSE 0 END, t.duedate ASC";
        } else {
            $sql = "SELECT id, title, duedate, status, NULL AS cat_name FROM todo ORDER BY CASE WHEN status = 'done' THEN 1 ELSE 0 END, CASE WHEN duedate IS NULL THEN 1 ELSE 0 END, duedate ASC";
        }

        $todos = $db->query($sql);
        if ($todos && $todos->num_rows > 0) {
            // Search Bar above Todo Stack
            echo "<div class=\"search-container\" style=\"max-width:700px;margin:30px auto 10px auto;padding:0;\">";
            echo "<div class=\"search-box\">";
            echo "<input type=\"text\" id=\"searchInput\" placeholder=\"Search todos...\" autocomplete=\"off\">";
            echo "<div id=\"searchSuggestions\" class=\"search-suggestions\"></div>";
            echo "</div>";
            echo "</div>";

            echo "<div class=\"todo-list-container\" style=\"max-width:700px;margin:10px auto;\">";
            // Heading and column headers
            echo "<h3 style=\"margin:12px 16px 6px 16px;font-size:18px;color:#222;text-align:center\">Todo Stack</h3>";
            // headers laid out as explicit columns: date / title / category / action
            echo "<div class=\"todo-headers\" style=\"display:flex;align-items:center;padding:8px 16px;font-weight:700;color:#444;border-bottom:1px solid #eee;\">";
            echo "<div style=\"width:15%;\"><span class=\"hdr-date\">Due Date</span></div>";
            echo "<div style=\"width:50%;\"><span class=\"hdr-title\">Title</span></div>";
            echo "<div style=\"width:24%;text-align:right;padding-right:16px\"><span class=\"hdr-cat\" style=\"font-weight:700\">Category Name</span></div>";
            echo "<div style=\"width:12%;text-align:right;padding-left:12px\"><span class=\"hdr-action\" style=\"font-size:0.95em;color:#666\">Action</span></div>";
            echo "</div>";
            echo "<ul class=\"todo-list\" style=\"list-style:none;padding:0;margin:0;\">";
            while ($t = $todos->fetch_assoc()) {
                $id = $t['id'];
                $title = htmlspecialchars($t['title']);
                $cat = htmlspecialchars($t['cat_name'] ?? '');
                $rawDue = $t['duedate'] ?? '';
                $dueDate = !empty($rawDue) ? date('Y-m-d', strtotime($rawDue)) : 'No date';
                // treat NULL/empty status as 'pending' for display and logic
                $status = !empty($t['status']) ? $t['status'] : 'pending';
                $isDone = ($status === 'done');
                // detect overdue: due date exists and is before today
                $isOverdue = false;
                if (!empty($rawDue)) {
                    $isOverdue = (strtotime($rawDue) < strtotime(date('Y-m-d')));
                }
                $dateStyle = $isOverdue ? 'color:#d9534f;font-size:0.9em;' : 'color:#666;font-size:0.9em;';
                $titleStyle = $isOverdue ? 'font-weight:600;color:#d9534f' : 'font-weight:600';
                echo "<li class=\"todo-item\" style=\"padding:12px 16px;border-bottom:1px solid #eee;\">";
                echo "<div style=\"display:flex;align-items:center;\">";

                // date column
                echo "<div style=\"width:15%;\"><span class=\"todo-date\" style=\"{$dateStyle}\">{$dueDate}</span></div>";

                // title column (make only the title clickable)
                echo "<div style=\"width:50%;\"><a href=\"viewTodo.php?id={$id}\" class=\"todo-link\" style=\"text-decoration:none;color:inherit;{$titleStyle}\">{$title}</a></div>";

                // category column (add right padding to create gap)
                echo "<div style=\"width:24%;text-align:right;color:#666;padding-right:16px\">{$cat}</div>";

                // action column (add left padding so buttons sit farther right)
                echo "<div style=\"width:12%;text-align:right;padding-left:12px\">";
                if (!$isDone) {
                    echo "<form action=\"updateStatus.php\" method=\"POST\" style=\"margin:0;display:inline-flex;align-items:center;justify-content:flex-end;\">";
                    echo "<input type=\"hidden\" name=\"todo_id\" value=\"{$id}\">";
                    echo "<button type=\"submit\" class=\"done-btn\" title=\"Mark as Done\" style=\"background:none;border:2px solid #28a745;color:#28a745;border-radius:4px;padding:6px 14px;cursor:pointer;transition:all 0.15s\">Done</button>";
                    echo "</form>";
                } else {
                    // show checkmark and a Delete button next to it
                    echo "<div style=\"display:inline-flex;gap:8px;align-items:center;justify-content:flex-end\">";
                    echo "<span style=\"color:#28a745;font-size:20px\" title=\"Completed\">✓</span>";
                    echo "<form action=\"deleteTodo.php\" method=\"POST\" style=\"margin:0\">";
                    echo "<input type=\"hidden\" name=\"todo_id\" value=\"{$id}\">";
                    echo "<button type=\"submit\" class=\"delete-btn\" title=\"Delete todo\" style=\"background:none;border:2px solid #dc3545;color:#dc3545;border-radius:4px;padding:6px 10px;cursor:pointer;transition:all 0.15s\">Delete</button>";
                    echo "</form>";
                    echo "</div>";
                }
                echo "</div>";

                echo "</div>"; // row flex
                echo "</li>";
            }
            echo "</ul></div>";
        }
        $db->close();
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const suggestionsBox = document.getElementById('searchSuggestions');
            let searchTimeout = null;

            // Close suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });

            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Hide suggestions if query is too short
                if (query.length < 2) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                // Set new timeout to avoid too many requests
                searchTimeout = setTimeout(() => {
                    fetch(`search_todos.php?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(suggestions => {
                            suggestionsBox.innerHTML = '';

                            if (suggestions.length === 0) {
                                suggestionsBox.style.display = 'none';
                                return;
                            }

                            suggestions.forEach(todo => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';

                                // Create the content with title and category
                                const content = `
                                    <div class="suggestion-title">${escapeHtml(todo.title)}</div>
                                    <div class="suggestion-category">${todo.category || 'No category'}</div>
                                `;

                                div.innerHTML = content;

                                // Add click handler to go to todo details
                                div.addEventListener('click', () => {
                                    window.location.href = `viewTodo.php?id=${todo.id}`;
                                });

                                suggestionsBox.appendChild(div);
                            });

                            suggestionsBox.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching suggestions:', error);
                        });
                }, 300); // Wait 300ms after last keystroke
            });

            // Helper function to escape HTML
            function escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        });
    </script>

    <style>
        /* small styles for the todo list on home page */
        .todo-list-container {
            background: #fff;
            padding: 6px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.06);
        }

        .todo-item:hover {
            background: #f7f7f8;
        }

        .todo-title {
            display: inline-block;
        }

        .todo-cat {
            white-space: nowrap;
        }

        .hdr-date {
            min-width: 85px;
        }

        .todo-date {
            font-family: monospace;
        }
    </style>

</body>

</html>