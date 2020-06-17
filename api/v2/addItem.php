<?php
require('../src/headers_v2.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

$response = '{"error": 500}';
if($connect = mysqli_connect($host, $user, $password, $DBName)) {
    $jsonData = ProcessingFiles::getRequestJsonData();
    $db_request = "INSERT INTO ToDos (id, text) VALUES (NULL, ?);";
    $stmt = mysqli_prepare($connect, $db_request);
    mysqli_stmt_bind_param($stmt, 's', $jsonData['text']);

    if (mysqli_stmt_execute($stmt)) {
        $response  = json_encode(['id' => mysqli_insert_id($connect)]);
    }
    mysqli_close($connect);
}
echo $response;

