<?php
$servername = "shinkansen.proxy.rlwy.net";
$username = "root";
$password = "vtEYOwzOZYCNwFqkaEqfEowwYWKKlmUr";
$dbname = "railway";
$port = 17416;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>