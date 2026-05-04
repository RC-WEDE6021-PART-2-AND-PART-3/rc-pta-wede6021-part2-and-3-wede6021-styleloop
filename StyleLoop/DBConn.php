<?php
// DBConn.php
// This file creates one reusable MySQLi database connection for the whole StyleLoop web application.
// It is included by all pages that need to communicate with the ClothingStore database.

$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "ClothingStore";

$conn = new mysqli($serverName, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
