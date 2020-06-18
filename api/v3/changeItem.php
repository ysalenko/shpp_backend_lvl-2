<?php
require('../src/headers_v3.php');
if($_SERVER['REQUEST_METHOD'] != 'PUT') {
    die('Unacceptable request method');
}

session_start();
if(!isset($_SESSION['userId'])) {
    require_once ('logout.php');
    $response = ['message' => 'Unauthorised', 'ok'=>false, 'error' => 400];
    echo json_encode($response);
    die();
}

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');
try {
    $pdo = getPDOConnection();
    $jsonData = ProcessingFiles::getRequestJsonData();
    $update = "UPDATE ToDos SET text=?, checked=? WHERE userId=? AND id=?";
    $stmt = $pdo->prepare($update);
    $stmt->execute([($jsonData['text']), (int)$jsonData['checked'], $_SESSION['userId'], (int)$jsonData['id']]);
    $response = ['ok'=> true];

} catch (PDOException $ex) {
    $response = ['error'=>500, 'ok'=>false, 'message'=>$ex->getMessage()];
}
echo json_encode($response);