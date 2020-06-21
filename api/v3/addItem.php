<?php
require('../src/headers.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}

require_once ('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

try {
    $pdo = getPDOConnection();
    $text = ProcessingFiles::getRequestJsonData()['text'];
    $dbRequest = "INSERT INTO $dataTblName (userId, id, text) VALUES (:usrId, NULL, :text);";
    $stmt = $pdo->prepare($dbRequest);
    $stmt->bindValue('usrId', $_SESSION['userId'], PDO::PARAM_INT);
    $stmt->bindValue('text', $text, PDO::PARAM_STR);
    if($stmt->execute()) {
        $response = ['id' =>$pdo->lastInsertId()];
    } else {
        $response = setError('cant add item to database', 500);
    }
} catch (PDOException $ex) {
    $response = setError('cant connect to database', 500);
}
echo json_encode($response);
