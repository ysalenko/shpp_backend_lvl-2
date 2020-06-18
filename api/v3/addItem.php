<?php
require('../src/headers_v3.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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
    $text = ProcessingFiles::getRequestJsonData()['text'];
    $dbRequest = "INSERT INTO $dataTblName (userId, id, text) VALUES (:usrId, NULL, :text);";
    $stmt = $pdo->prepare($dbRequest);
    $stmt->bindValue('usrId', $_SESSION['userId'], PDO::PARAM_INT);
    $stmt->bindValue('text', $text, PDO::PARAM_STR);
    $stmt->execute();
    $response = ['id' =>$pdo->lastInsertId()];
} catch (PDOException $ex) {
    $response = ['error'=>500, 'message'=>$ex->getMessage(), 'ok'=>false];
}
echo json_encode($response);
