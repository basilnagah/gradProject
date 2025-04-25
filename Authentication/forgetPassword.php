<?php
require_once '../conn/connection.php';
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Africa/Cairo'); // Change to your correct timezone





header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

error_reporting(E_ALL);
ini_set('display_errors', 1);





$inputData = json_decode(file_get_contents("php://input") , true);
if(empty($inputData)){
    $data = $_POST;
}else{
    $data = $inputData;
}

if (!isset($data['email']) || empty($data['email'])) {
    echo json_encode(["status" => 400, "message" => "Email is required"]);
    exit;
}


$email = trim($data['email']); // Remove spaces
$otp = rand(100000, 999999);  // Generate a 6-digit OTP
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP valid for 5 minutes





// Check if email exists
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo json_encode(["status" => 404, "message" => "Email not found"]);
    exit;
}





// Store OTP in the database
$query = "UPDATE users SET reset_otp = '$otp', otp_expiry = '$expiry' WHERE email = '$email'";
$result = mysqli_query($conn, $query);







// Send OTP via email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'basilnagah23@gmail.com'; // Change to your email
    $mail->Password = 'tihb glvw jpdo ldak'; // Use App Password, not your real password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('basil.route@gmail.com', 'basil wa7ed');
    $mail->addAddress($email);

    $mail->Subject = 'Your Password Reset OTP';
    $mail->Body = "Your OTP for password reset is: $otp. It expires in 10 minutes.";

    $mail->send();
    if ($mail->send()) {
        echo json_encode(["status" => 200, "message" => "OTP sent to your email"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => 500, "message" => "Email failed: " . $mail->ErrorInfo]);
}
?>