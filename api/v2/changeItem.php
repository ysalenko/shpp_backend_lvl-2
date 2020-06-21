<?php
require('../src/headers.php');
if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    die('Unacceptable request method');
}
require_once('../src/DBConfig.php');

require_once('../src/sessionControl.php');
sessionStart();

if ($connect = mysqli_connect($host, $user, $password, $DBName)) {
    require_once('../src/ProcessingFiles.php');
    $itemData = ProcessingFiles::getRequestJsonData();
    $update = "UPDATE $dataTblName SET text=?, checked=? WHERE userId=? AND id=?";
    $stmt = mysqli_prepare($connect, $update);
    $checked = $itemData['checked'] ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "siii",
        $itemData['text'], $checked, $_SESSION['userId'], $itemData['id']);

    if (mysqli_stmt_execute($stmt)) {
        $response = ["ok" => true];
    } else {
        $response = setError('cant change data in database', 500);
    }

    mysqli_close($connect);
} else {
    $response = setError('Unable to connect to database', 500);
}

echo json_encode($response);