<?php
require('../src/headers_v3.php');
if($_SERVER['REQUEST_METHOD'] != 'GET') {
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
require ('ToDo.php');
try {
    $pdo = getPDOConnection();
    $select = "SELECT id, text, checked FROM $dataTblName WHERE userId={$_SESSION['userId']}";
    $stmt = $pdo->query($select);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'ToDo');
    $result = $stmt->fetchAll();
    $response = ['items' => $result];
} catch (PDOException $ex) {
    $response = ['error'=>500, 'message'=>$ex->getMessage(), 'ok'=>false];
}
echo json_encode($response);
