<?php
include "../conn/connection.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

error_reporting(E_ALL);
ini_set('display_errors', 1);


$inputData = json_decode(file_get_contents("php://input"), true);
if (empty($inputData)) {
    $data = $_POST;
} else {
    $data = $inputData;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset(
        $data["store_id"],
        $data["name"],
        $data["description"],
        $data["price"],
        $data["onSale"],
        $data["stock_quantity"],
        $data["category"],
        $data["brand"],
        $data["images"],
        $data["discount"]
    )) {

        $store_id = $data["store_id"];
        $name = $data["name"];
        $description = $data["description"];
        $price = $data["price"];
        $onSale = $data["onSale"];
        $stock_quantity = $data["stock_quantity"];
        $category = $data["category"];
        $brand = $data["brand"];
        $images = json_encode($data["images"], JSON_UNESCAPED_SLASHES);
        $discount = $data["discount"];

        $query = "INSERT INTO products (store_id, name, description, price, onSale, stock_quantity, category, brand, images, discount) 
        VALUES ('$store_id', '$name', '$description', '$price', '$onSale', '$stock_quantity', '$category', '$brand', '$images', '$discount')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo json_encode(["status" => 200, "message" => "Product added successfully"]);
        } else {
            echo json_encode([
                "status" => 500,
                "message" => "Server error: " . mysqli_error($conn) // Show MySQL error
            ]);
        }
    } else {
        echo json_encode(["status" => 404, "message" => "all inputs is required"]);
    }
} else {
    $response["success"] = false;
    $response["message"] = "Invalid request method.";
}
