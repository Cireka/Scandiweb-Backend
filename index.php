<?php

spl_autoload_register(function ($class){
    require __DIR__ . "/src/product/$class.php";
});
header("Content-type: application/json; charset=UTF-8");
set_exception_handler("ErrorHandler::handleException");

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

switch ($method){
    case "GET":
        // product controller for get reqs
        break;
    case "POST":
        // product controller for get POST
        break;
    case "DELETE":
        // product controller for DEl reqs
        break;
    default:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
}
