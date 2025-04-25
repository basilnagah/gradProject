<?php
require_once '../conn/connection.php'; // Include database connection
require_once '../vendor/autoload.php';  // Import JWT Library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Handle preflight request (important for CORS issues)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}



$requestMethod = $_SERVER["REQUEST_METHOD"];

$secret_key = "Vygt(|.C;tqB@;-g6!Kz5*_Cd]cqOSR](>%HK#=H0p#(DY{wSgM7ym:N>ofA8H(";  // Keep this secret and safe

// Get the token from the request header
$headers = getallheaders();
if (!isset($headers['token'])) {
    echo json_encode(["status" => 401, "message" => "Token is required"]);
    exit;
}

$token = $headers['token'];


try {
    // Decode the token
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

    // Fetch stores from the database
    $sql = "SELECT * FROM store"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $stores = [];
        while ($row = $result->fetch_assoc()) {
            $stores[] = $row;
        }
        echo json_encode(["status" => 200, "stores" => $stores]);
    } else {
        echo json_encode(["status" => 404, "message" => "No stores found"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => 401, "message" => "Invalid token", "error" => $e->getMessage()]);
}

$conn->close();
?>