<?php

$host = "localhost";
$user = "root";
$password = "";   // your MySQL password
$database = "shradhay";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
