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
        /* Home and Logout button styling: positioned at the top-left and top-right of the page */
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            z-index: 1000;
            transition: background 0.15s ease;
        }
        .home-btn:hover {
            background: #218838;
        }
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            z-index: 1000;
            transition: background 0.15s ease;
        }
        .logout-btn:hover {
            background: #c82333;
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
    <a href="home.php" class="home-btn">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>
    <div class="container">
        <h2>Welcome to Todo Manager</h2>
        <form action="todo.php" method="get">
            <button type="submit" class="add-btn" title="Add New Todo">+</button>
        </form>
    </div>
</body>
</html>