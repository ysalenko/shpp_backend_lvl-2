<?php
require('../src/headers_v2.php');

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');
$response = '{"error": 500}';
if($connect = mysqli_connect($host, $user, $password, $dbName)) {
    $id = ProcessingFiles::getRequestJsonData()['id'];
    $delete = "DELETE FROM ToDos WHERE id = $id";

    $result = mysqli_query($connect, $delete);
    mysqli_close($connect);
    $response = $result ? '{"ok": true}' : '{"ok": false}';
}
echo $response;
