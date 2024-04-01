<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, DELETE, PATCH");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
    header("HTTP/1.1 200 OK");
    exit();
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PATCH");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");


