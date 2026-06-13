<?php
// config/db.php

$host = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty
$database = "kuet_film_club";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>