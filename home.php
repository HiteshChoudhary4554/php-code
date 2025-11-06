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
    <!-- Logout button (HTML + CSS only). Update href if your logout handler is at a different path -->
    <a href="logout.php" class="logout-btn">Logout</a>   
    <div class="container">
        <h2>Welcome to Todo Manager</h2>
        <form action="todo.php" method="get">
            <button type="submit" class="add-btn" title="Add New Todo">+</button>
        </form>
    </div>
</body>
</html>