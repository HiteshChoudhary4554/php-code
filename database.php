<?php
class Database
{
    private $conn; 

    // Constructor (optional)
    function __construct()
    {
        // you can leave this empty or put default values here
    }

    // Function to connect to the database
    function connect($servername, $username, $password, $dbname)
    {
        // Create connection
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else {
        }

        // Return the connection so you can use it
        return $this->conn;
    }
}
?>
