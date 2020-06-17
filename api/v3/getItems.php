<?php
require('../src/headers_v3.php');
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    die('Unacceptable request method');
}

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');
require ('ToDo.php');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBName", $user, $password);
    $select = "SELECT id, text, checked FROM $dataTblName";
    $stmt = $pdo->query($select);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'ToDo');
    $result = $stmt->fetchAll();
    $response = json_encode(['items' => $result]);
} catch (PDOException $ex) {
    $response = '{"error": 500}';
}
echo $response;
