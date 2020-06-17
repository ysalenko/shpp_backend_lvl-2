<?php
require('../src/headers_v3.php');

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBName", $user, $password);
    $id = ProcessingFiles::getRequestJsonData()['id'];
    $delete = "DELETE FROM ToDos WHERE id = ?";
    $stmt = $pdo->prepare($delete);
    $result = $stmt->execute([$id]);
    $response = $result ? '{"ok": true}' : '{"ok": false}';
} catch (PDOException $ex) {
    $response = '{"error": 500}';
}
echo $response;