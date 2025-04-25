<?php
require_once('../conn/connection.php');

$url = $_GET['url'] ?? '';

switch ($url) {
    case 'login':
        require_once('../Authentication/login.php');
        break;

    case 'register':
        require_once('../Authentication/register.php');
        break;

    case 'product':
        require_once('../products/addProduct.php');
        break;

    case 'store':
        require_once('../store/getAllStores.php');
        break;

    default:
        echo "<h1>Welcome to My PHP App</h1>";
        echo "<p><a href='/login'>Login</a> | <a href='/register'>Register</a></p>";
        break;
}
?>
