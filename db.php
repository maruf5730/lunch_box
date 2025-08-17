<?php
$host = "localhost";
$user = "root";   // default user in XAMPP
$pass = "";       // default password is empty
$db   = "lunch_box";  // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
