<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Register Page</title>
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

    input[type="text"],
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
      background: #28a745;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
    }

    .login-link {
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
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['gmailid'];
    $Password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hitesh books store";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql =  "INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`)
        VALUES ('$firstname', '$lastname', '$email', '$Password')";

    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
  ?>
  <div class="container">
    <form method="post" action="register.php">
      <h2>Register</h2>
      <input type="text" name="firstname" placeholder="First Name" required />
      <input type="text" name="lastname" placeholder="Last Name" required />
      <input type="email" name="gmailid" placeholder="Gmail ID" required />
      <input
        type="password"
        name="password"
        placeholder="Password"
        required />
      <input
        type="password"
        name="confirmpassword"
        placeholder="Confirm Password"
        required />
      <button type="submit">Register</button>
      <a class="login-link" href="login.php">Already have an account? Login</a>
    </form>
  </div>
</body>

</html>