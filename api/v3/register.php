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
    checkLogin($pdo, $requestData['login']);
    $addUser = "INSERT INTO {$GLOBALS['usersTblName']} (userId, login, password) VALUES (null,:lgn, :pswrd);";
    $stmt = $pdo->prepare($addUser);
    $stmt->bindParam('lgn', $requestData['login'], PDO::PARAM_STR);
    $stmt->bindParam('pswrd', $requestData['pass'], PDO::PARAM_STR);
    if($stmt->execute()){
        $response = ["ok" => true];
    } else {
        $response = setError("couldn't add new entry", 500);
    }

} catch (PDOException $ex) {
    $response = setError('Unable to process operation', 500);
}
echo json_encode($response);


function checkLogin($pdoConn, $login) {
    $tblName = $GLOBALS['usersTblName'];
    $extractAllLogins = "SELECT login FROM $tblName WHERE login LIKE :lgn;";
    $stmt = $pdoConn->prepare($extractAllLogins);
    $stmt->bindParam('lgn', $login, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();
    if($result) {
        http_response_code(400);
        echo '{"ok": false, "error" : "Login Already exists"}';
        die();
    }
}