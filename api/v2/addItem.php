<?php
require('../src/headers.php');
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}

require_once('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

if ($connect = mysqli_connect($host, $user, $password, $DBName)) {
    $jsonData = ProcessingFiles::getRequestJsonData();
    $dbRequest = "INSERT INTO $dataTblName (userId, id, text) VALUES (?, NULL, ?);";
    $stmt = mysqli_prepare($connect, $dbRequest);
    mysqli_stmt_bind_param($stmt, 'is', $_SESSION['userId'], $jsonData['text']);

    if (mysqli_stmt_execute($stmt)) {
        $response = ['id' => mysqli_insert_id($connect)];
    } else {
        $response = setError('cant add item to database', 500);
    }
    mysqli_close($connect);
} else {
    $response = setError('cant connect to database', 500);
}
echo json_encode($response);


