<?php
$servername = "ballast.proxy.rlwy.net";
$username = "root";
$password = "ObWqJELmdiJJHRehCnAqIXbgdoROebNm";
$dbname = "railway";
$port = 29077;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
