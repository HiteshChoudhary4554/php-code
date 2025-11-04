<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login Page</title>
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
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 18px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    // user authnetication sytem via database connectivity
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $servername = "localhost";
        $username = "root";
        $Password = "";
        $dbname = "hitesh books store";

        // Create connection
        $conn = new mysqli($servername, $username, $Password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = $conn->prepare( "SELECT password FROM `users` WHERE email = ?");
        $query->bind_param("s",$email);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows === 1){
            $row = $result->fetch_assoc();
            $dbPassword = $row['password'];

            if ($password === $dbPassword) {
                header("Location: home.php");
            }
            else{
                header("Location: login.php");
            }
        }
        $conn->close();
    }
    ?>
    <div class="container">
        <form method="post" action="">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a class="register-link" href="register.php"> Don't have an account? Register</a>
        </form>
    </div>
</body>
</html>
