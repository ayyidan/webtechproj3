<?php
// Database connection settings
$servername = "localhost";  
$username = "root"; 
$password = "root"; 
$dbname = "web_technology_class"; 

// Create a new connection to the database using MySQLi
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
