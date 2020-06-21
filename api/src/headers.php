<?php

$http_origin = $_SERVER['HTTP_ORIGIN'];

if ($http_origin == "http://www.frontend.local" ||
    $http_origin == "http://www.frontend_v2.local" ||
    $http_origin == "http://www.frontend_v3.local")
{
    header("Access-Control-Allow-Origin: $http_origin");
}
header('Access-Control-Allow-Credentials: true');
header('Accept: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Methods: PUT, DELETE, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    die();
}
header('Content-type: application/json');