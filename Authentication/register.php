<?php 
require_once '../conn/connection.php';
require_once 'function.php';

error_reporting(0);




header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];


if( $requestMethod == 'POST' ){

    $inputData = json_decode(file_get_contents("php://input") , true);
    if(empty($inputData)){
        $storeCustomer = createAccount($_POST);
    }else{
        $storeCustomer = createAccount($inputData);
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