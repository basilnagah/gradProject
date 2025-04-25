<?php 
require_once '../conn/connection.php';
require_once 'function.php';
require_once '../vendor/autoload.php';  // Import JWT Library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

error_reporting(E_ALL);
ini_set('display_errors', 1);
$requestMethod = $_SERVER["REQUEST_METHOD"];




if( $requestMethod == 'POST' ){

    $inputData = json_decode(file_get_contents("php://input") , true);
    if(empty($inputData)){
        $storeCustomer = loginToAccount($_POST);
    }else{
        $storeCustomer = loginToAccount($inputData);
    }

    echo $storeCustomer;

}else{
    $data = [
        'status' => 405,
        'message' => "$requestMethod method not allowed",
    ];
    header("HTTP/1.0 405 Method not allowed");
    echo json_encode($data);
}




?>