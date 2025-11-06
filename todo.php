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
<<<<<<< HEAD
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
=======

        input[type="text"],
        textarea {
>>>>>>> 05638eff0e1ca795c54cb6fb0bbce99fbaf02d72
            width: 100%;
            padding: 10px;
            margin: 6px 0 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
<<<<<<< HEAD
        textarea { resize: vertical; }
=======

>>>>>>> 05638eff0e1ca795c54cb6fb0bbce99fbaf02d72
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
    <!-- Logout button (HTML + CSS only). Update href if your logout handler is at a different path -->
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