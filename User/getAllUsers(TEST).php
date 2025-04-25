<?php
require_once '../conn/connection.php'; // Include database connection
require_once '../vendor/autoload.php';  // Import JWT Library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");


$secret_key = "Vygt(|.C;tqB@;-g6!Kz5*_Cd]cqOSR](>%HK#=H0p#(DY{wSgM7ym:N>ofA8H(";  // Keep this secret and safe

// Get the token from the request header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["status" => 401, "message" => "Token is required"]);
    exit;
}

$token = $headers['Authorization'];


try {
    // Decode the token
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

    // Fetch users from the database
    $sql = "SELECT id, name, email FROM users"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(["status" => 200, "users" => $users]);
    } else {
        echo json_encode(["status" => 404, "message" => "No users found"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => 401, "message" => "Invalid token", "error" => $e->getMessage()]);
}

$conn->close();
?>