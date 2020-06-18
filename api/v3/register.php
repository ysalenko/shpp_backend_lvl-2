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
    checkLogin($pdo, $requestData['login']);
    $addUser = "INSERT INTO {$GLOBALS['usersTblName']} (userId, login, password) VALUES (null,:lgn, :pswrd);";
    $stmt = $pdo->prepare($addUser);
    $stmt->bindParam('lgn', $requestData['login'], PDO::PARAM_STR);
    $stmt->bindParam('pswrd', $requestData['pass'], PDO::PARAM_STR);
    $result = $stmt->execute();
    $response = $result ? '{"ok": true}' : '{"error": 500, "ok": false}';

} catch (PDOException $ex) {
    $message = $ex->getMessage();
    $response = '{"error": 500, "ok": false}';
}
echo $response;


function checkLogin($pdoConn, $login) {
    $tblName = $GLOBALS['usersTblName'];
    $extractAllLogins = "SELECT login FROM $tblName WHERE login LIKE :lgn;";
    $stmt = $pdoConn->prepare($extractAllLogins);
    $stmt->bindParam('lgn', $login, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();
    if($result) {
        echo '{"error": 400, "ok": false, "message" : "Login Already exists"}';
        die();
    }
}