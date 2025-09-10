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
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 18px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
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
        *{
            color: white;
            background-color: black;
        }
    </style>
</head>
<body >
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // You can add code here to save the todo to a database or file
        // For now, just redirect to home.php after submit
        header("Location: home.php");
        exit();
    }
    ?>
    <div class="container">
        <h2>Add Todo</h2>
        <form method="post" action="">
            <input type="text" name="title" placeholder="Todo Title" required>
            <textarea name="description" rows="4" placeholder="Task Description" required></textarea>
            <button type="submit">Add Todo</button>
        </form>
        </div>
</body>
</html>