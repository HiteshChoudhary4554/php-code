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
        *{
            color: white;
            background-color: black;
        }
    </style>
</head>
<body>
    <?php
    ?>
    <div class="container">
        <h2>Welcome to Home Page</h2>
        <form action="todo.php" method="get">
            <button type="submit" class="add-btn" title="Add New Todo">+</button>
        </form>
        </div>
</body>
</html>