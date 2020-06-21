<?php
require('../src/headers.php');
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    die('Unacceptable request method');
}
require_once ('../src/sessionControl.php');
sessionStart();

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
    $response = setError('Unable to process operation', 500);
}
echo json_encode($response);
