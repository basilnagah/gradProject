<?php
require_once '../conn/connection.php';
require_once '../vendor/autoload.php';  // Import JWT Library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



function error422($message)
{
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 unprocessable entity");
    echo json_encode($data);
    exit();
}


function createAccount($customerInput)
{
    global $conn;

    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $password = mysqli_real_escape_string($conn, $customerInput['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $customerInput['confirmPassword']);
    $age = mysqli_real_escape_string($conn, $customerInput['age']);
    $gender = mysqli_real_escape_string($conn, $customerInput['gender']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    $errors = [];
    // name
    if (empty(trim($name))) {
        $errors[] = 'name is required';
    } else if (strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters long';
    } else if (strlen($name) > 15) {
        $errors[] = 'Name cannot be longer than 15 characters';
    }



    // email
    if (empty(trim($email))) {
        $errors[] = 'email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'email must be valid';
    } else {
        $checkEmailQuery = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
        $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($checkEmailResult) > 0) {
            $errors[] = "Email is already registered";
        }
    }

    // password
    if (empty(trim($password))) {
        $errors[] = 'password is required';
    } else if ($password != $confirmPassword) {
        $errors[] = 'password must match';
    }

    // phone
    if (empty(trim($phone))) {
        $errors[] = 'phone$phone is required';
    } else {
        // 🔍 Check if phone number already exists
        $checkPhoneQuery = "SELECT id FROM users WHERE phone = '$phone' LIMIT 1";
        $checkPhoneResult = mysqli_query($conn, $checkPhoneQuery);

        if (mysqli_num_rows($checkPhoneResult) > 0) {
            $errors[] = "Phone number is already registered";
        }
    }


    // check errors
    if (!empty($errors)) {
        return json_encode([
            'status' => 422,
            'errors' => $errors
        ]);
    }

    // hash password
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (name, email, phone, password) 
              VALUES ('$name', '$email', '$phone', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return json_encode([
            'status' => 201,
            'message' => 'User created successfully'
        ]);
    } else {
        return json_encode([
            'status' => 500,
            'message' => 'Internal server error'
        ]);
    }
}


function loginToAccount($customerInput)
{
    global $conn;

    $secret_key = "Vygt(|.C;tqB@;-g6!Kz5*_Cd]cqOSR](>%HK#=H0p#(DY{wSgM7ym:N>ofA8H(";  // Keep this secret and safe


    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $password = mysqli_real_escape_string($conn, $customerInput['password']);

    if (empty(trim($email))) {

        return error422('email is required');
    } else if (empty(trim($password))) {
        return error422('password is required');
    } else {
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);


        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if ($password == $user['password']) {

                $payload = [
                    "iss" => "http://localhost/eMall",  // Issuer
                    "iat" => time(),  // Issued at
                    "exp" => time() + (60 * 60),  // Expiration (1 hour)
                    "user_id" => $user['id'],
                    "email" => $user['email'],
                    "role" => $user['role'],
                ];

                $token = JWT::encode($payload, $secret_key, 'HS256');



                echo json_encode(
                    [
                        "status" => 200,
                        "message" => "Login successful",
                        "user" => [
                            "id" => $user['id'],
                            "name" => $user['name'],
                            "email" => $user['email'],
                            "role" => $user['role'],
                            "token" => $token
                        ],
                    ]
                );


            } else {
                echo json_encode(["status" => 401, "message" => "Invalid password"]);
            }
        } else {
            echo json_encode(["status" => 404, "message" => "email not found"]);
        }
    }
}
