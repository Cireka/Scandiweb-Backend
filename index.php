<?php
require "src/Database/DataBase.php";
require "src/Utils/ErrorHandler.php";
require_once "src/Utils/autoLoader.php";

spl_autoload_register('autoload');
header("Content-type: application/json; charset=UTF-8");
set_exception_handler("ErrorHandler::handleException");

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

$dsn =  "mysql:host=127.0.0.1;dbname=Scandiweb Product DB;port=3306;charset=utf8";
$db = new src\Database\DataBase($dsn,"root","3971");

$productController = new src\product\productController($db);

switch ($method){
    case "GET":
    $productController->handleGet($uri);
        break;
    case "POST":
        $productController->handlePost($uri);
        break;
    case "DELETE":
       $productController->handleDelete($uri);
        break;
    default:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
}
