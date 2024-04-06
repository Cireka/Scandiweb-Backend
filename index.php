<?php
require "src/Utils/ErrorHandler.php";
require_once "src/Utils/autoLoader.php";


spl_autoload_register('autoload');
header("Content-type: application/json; charset=UTF-8");
set_exception_handler("ErrorHandler::handleException");

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$dsn = "mysql:host=sql11.freesqldatabase.com;dbname=sql11695834;port=3306;charset=utf8";
$db = new src\Database\DataBase($dsn, "sql11695834", "GhiPjSvsDF");

$productController = new src\product\ProductController($db);



switch ($method) {
    case "GET":
        $productController->handleGet($uri);
        break;
    case "POST":
        $productController->handlePost($uri);
        break;
    case "DELETE":
        $productController->handleDelete($uri);
        break;
    case "PATCH":
        $productController->handlePatch($uri);
        break;
    default:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
}
