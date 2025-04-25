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



if (!isset($data['email']) || !isset($data['new_password'])) {
    echo json_encode(["status" => 400, "message" => "Email and new password are required"]);
    exit;
}

$email = $data['email'];
$newPassword = $data['new_password'];

$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo json_encode(["status" => 404, "message" => "Email not found"]);
    exit;
}


// Update password in database
if($user['isVerified'] == 1){

    $query = "UPDATE users SET password = '$newPassword', reset_otp = NULL, otp_expiry = NULL , isVerified = 0 WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    echo json_encode(["status" => 200, "message" => "Password reset successful"]);
}else{
    echo json_encode(["status" => 400, "message" => "email is no verified"]);

}
?>
