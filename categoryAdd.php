<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="style.css">
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

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 6px 0 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #138496;
        }

        /* Table Styling */
        .table-container {
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 14px;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            color: #444;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .sr-no {
            width: 80px;
            text-align: center;
        }

        /* Delete button styling */
        .delete-btn {
            padding: 6px 12px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .action-column {
            width: 100px;
            text-align: center;
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

    // add category in database
    // database connection
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

    // Handle category deletion
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteQuery = "DELETE FROM category WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Query to select all data from category table
    $result = $conn->query("SELECT * FROM category ORDER BY id");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoryName = $_POST['category_name'];

        $sql = "INSERT INTO `category` (`category`) VALUES ('$categoryName')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    ?>
    <!-- Navigation buttons -->
    <a href="home.php" class="homeUI-btn">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>

    <div class="container">
        <h2>Add Category</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" placeholder="Enter category name" required>
            </div>
            <button type="submit">Add Category</button>
        </form>

        <!-- Categories Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="sr-no">Sr. No</th>
                        <th>Category Name</th>
                        <th class="action-column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1; // Initialize counter
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='sr-no'>" . $index . "</td>"; // Use index instead of id
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td class='action-column'>";
                        echo "<a href='?delete=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                        $index++; // Increment counter
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>