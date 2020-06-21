<?php
require('../src/headers.php');

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    die('Unacceptable request method');
}
require_once ('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

try {
    $pdo = getPDOConnection();
    $id = ProcessingFiles::getRequestJsonData()['id'];
    $deleteQuery = "DELETE FROM ToDos WHERE userId=? AND id=?";
    $stmt = $pdo->prepare($deleteQuery);
    if ($stmt->execute([$_SESSION['userId'], $id])) {
        $response = ["ok" => true];
    } else {
        $response = setError('cant delete item', 500);
    }
} catch (PDOException $ex) {
    $response = setError('Unable to process operation', 500);
}

echo json_encode($response);