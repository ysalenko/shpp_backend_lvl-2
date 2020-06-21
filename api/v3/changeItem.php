<?php
require('../src/headers.php');
if($_SERVER['REQUEST_METHOD'] != 'PUT') {
    die('Unacceptable request method');
}
require_once ('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

try {
    $itemData = ProcessingFiles::getRequestJsonData();

    $pdo = getPDOConnection();
    $updateQuery = "UPDATE ToDos SET text=?, checked=? WHERE userId=? AND id=?";
    $stmt = $pdo->prepare($updateQuery);
    $result = $stmt->execute([($itemData['text']), (int)$itemData['checked'], $_SESSION['userId'], (int)$itemData['id']]);
    if ($result) {
        $response = ["ok" => true];
    } else {
        $response = setError('cant change data in database', 500);
    }
} catch (PDOException $ex) {
    $response = setError('Unable to connect to database', 500);
}
echo json_encode($response);