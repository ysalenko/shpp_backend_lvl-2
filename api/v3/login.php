<?php
require('../src/headers.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

$requestData = ProcessingFiles::getRequestJsonData();
try {
    $pdo = getPDOConnection();
    $request = "SELECT userId FROM $usersTblName WHERE login LIKE :lgn AND password LIKE :pswrd;";
    $stmt = $pdo->prepare($request);
    $stmt->bindValue('lgn', $requestData['login'], PDO::PARAM_STR);
    $stmt->bindValue('pswrd', $requestData['pass'], PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        session_start();
        $_SESSION['userId'] = (int)$userData['userId'];
        $response = ['ok' => true];

    } else {
        $response= setError("Incorrect Login or a Password", 400);
    }

} catch (PDOException $ex) {
    $response = setError('Unable to process operation', 500);
}
echo json_encode($response);