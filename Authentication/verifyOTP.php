<?php
require_once '../conn/connection.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$inputData = json_decode(file_get_contents("php://input") , true);
if(empty($inputData)){
    $data = $_POST;
}else{
    $data = $inputData;
}





if (!isset($data['email']) || !isset($data['otp'])) {
    echo json_encode(["status" => 400, "message" => "Email and OTP are required"]);
    exit;
}

$email = $data['email'];
$otp = $data['otp'];

// Verify OTP and expiry
$query = "SELECT * FROM users WHERE email = '$email' AND reset_otp = '$otp' AND otp_expiry > NOW()";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(["status" => 400, "message" => "Invalid or expired OTP"]);
    exit;
}

// OTP is valid, allow user to reset password
$queryAfter = "UPDATE users SET isVerified = 1 WHERE email = '$email'";
$resultAfter = mysqli_query($conn, $queryAfter);

if ($resultAfter) {
    echo json_encode(["status" => 200, "message" => "OTP verified. Proceed to reset password."]);
} else {
    echo json_encode(["status" => 500, "message" => "Error updating verification status.", "error" => mysqli_error($conn)]);
}
?>
