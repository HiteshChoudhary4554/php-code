<!DOCTYPE html>
<html>

<head>
    <title>Add Todo</title>
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

        input[type="text"], textarea, input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea { resize: vertical; }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Home and Logout button styling */
        .home-btn {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #28a745;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            z-index: 1000;
            transition: background 0.15s ease;
        }
        .home-btn:hover { background: #218838; }

        .logout-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #dc3545;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            z-index: 1000;
            transition: background 0.15s ease;
        }
        .logout-btn:hover { background: #c82333; }
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // You can add code here to save the todo to a database or file
        // For now, just redirect to home.php after submit
        header("Location: home.php");
        exit();
    }
    ?>
    <!-- Home and Logout buttons (HTML + CSS only). Update hrefs if needed -->
    <a href="home.php" class="home-btn">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>
    <div class="container">
        <h2>Add Todo</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="title">Todo Title</label>
                <input type="text" id="title" name="title" placeholder="Enter title" required>
            </div>
            <div class="form-group">
                <label for="description">Task Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the task" required></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date">
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="" disabled selected>Select category (will load from DB)</option>
                    <!-- Options will be populated from database in server-side code later -->
                </select>
            </div>
            <button type="submit">Add Todo</button>
        </form>
    </div>
</body>

</html>