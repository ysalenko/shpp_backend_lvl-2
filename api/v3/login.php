<?php
require('../src/headers_v3.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

$requestData = ProcessingFiles::getRequestJsonData();
try {
    $pdo = getPDOConnection();
    $request = "SELECT * FROM $usersTblName WHERE login LIKE :lgn";
    $stmt = $pdo->prepare($request);
    $stmt->bindParam('lgn', $requestData['login'], PDO::PARAM_STR);
    $result = $stmt->execute();
    if (!$stmt->rowCount()) {
        $response = ['message' => "Login incorrect", 'ok' => false, 'error' => 404];
    } elseif (($userData = $stmt->fetch(PDO::FETCH_ASSOC)['password']) !== $requestData['pass']) {
        $response = ['message' => "Incorrect password", 'ok' => false, 'error' => 400];
    } else {
        session_start();
        $_SESSION['userId'] = (int)$userData['userId'];
        $response = ['ok' => true];
    }

} catch (PDOException $ex) {
    $response = ['message' => $ex->getMessage(), 'ok' => false, 'error' => 500];
}
echo json_encode($response);