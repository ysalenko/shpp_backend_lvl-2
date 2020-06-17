<?php
require('../src/headers_v2.php');
if($_SERVER['REQUEST_METHOD'] != 'PUT') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
$response = '{"error": 500}';

if ($connect = mysqli_connect($host, $user, $password, $dbName)) {
    require_once('../src/ProcessingFiles.php');
    $jsonData = ProcessingFiles::getRequestJsonData();
    $update = "UPDATE ToDos SET text=?, checked=? WHERE id=?";
    $stmt = mysqli_prepare($connect, $update);
    $checked = $jsonData['checked'] ? 1 : 0;
    mysqli_stmt_bind_param($stmt, 'sii', $jsonData['text'], $checked, $jsonData['id']);
    $result = mysqli_stmt_execute($stmt);

    mysqli_close($connect);
    $response = $result ? '{"ok": true}' : '{"ok": false}';
}
echo $response;