<?php
require('../src/headers_v3.php');

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
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
    $id = ProcessingFiles::getRequestJsonData()['id'];
    $delete = "DELETE FROM ToDos WHERE userId=? AND id=?";
    $stmt = $pdo->prepare($delete);
    $result = $stmt->execute([$_SESSION['userId'],$id]);
    $response = ['ok'=>$result];
} catch (PDOException $ex) {
    $response = ['error'=>500, 'message'=>$ex->getMessage(), 'ok'=>false];
}
echo json_encode($response);