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

</body>
</html>
