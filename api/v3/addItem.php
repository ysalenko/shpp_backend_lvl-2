<?php
require('../src/headers_v3.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBName", $user, $password);
    $text = htmlentities(ProcessingFiles::getRequestJsonData()['text']);
    $db_request = "INSERT INTO ToDos (id, text) VALUES (NULL, ?);";
    $stmt = $pdo->prepare($db_request);
    $stmt->execute([$text]);
    $id = $pdo->lastInsertId();
    $response = "{\"id\": $id}";

} catch (PDOException $ex) {
    $response = '{"error": 500}';
}
echo $response;
