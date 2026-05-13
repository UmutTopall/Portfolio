<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>