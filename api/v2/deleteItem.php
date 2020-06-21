<?php
require('../src/headers.php');

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    die('Unacceptable request method');
}

require_once('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

if ($connect = mysqli_connect($host, $user, $password, $DBName)) {
    $id = ProcessingFiles::getRequestJsonData()['id'];
    $deleteQuery = "DELETE FROM $dataTblName WHERE userId=? AND id=?";
    $stmt = mysqli_prepare($connect, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $_SESSION['userId'], $id);
    if (mysqli_stmt_execute($stmt)) {
        $response = ["ok" => true];
    } else {
        $response = setError('cant delete item', 500);
    }
    mysqli_close($connect);
} else {
    $response = setError('Unable to connect to database', 500);
}
echo json_encode($response);
