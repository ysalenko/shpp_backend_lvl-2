<?php
require('../src/headers_v3.php');
if($_SERVER['REQUEST_METHOD'] != 'PUT') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBName", $user, $password);
    $jsonData = ProcessingFiles::getRequestJsonData();
    $update = "UPDATE ToDos SET text=?, checked=? WHERE id=?";
    $stmt = $pdo->prepare($update);
    $stmt->execute([htmlentities($jsonData['text']), (int)$jsonData['checked'], (int)$jsonData['id']]);
    $response = '{"ok": true}';
} catch (PDOException $ex) {
    $response = '{"error": 500}';
}
echo $response;