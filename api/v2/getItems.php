<?php
require('../src/headers.php');
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    die('Unacceptable request method');
}

require_once ('../src/sessionControl.php');
sessionStart();

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

if($connect = mysqli_connect($host, $user, $password, $DBName)) {
    $select = "SELECT id, text, checked FROM $dataTblName WHERE userId=?";
    $stmt= mysqli_stmt_init($connect);
    mysqli_stmt_prepare($stmt,$select);
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['userId']);

    if (mysqli_stmt_execute($stmt)) {
        $items['items'] = [];
        $extractedRows = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_array($extractedRows,MYSQLI_ASSOC)) {
            $items['items'][] = $row;
        }
        $response = $items;
    } else {
        $response = setError('cant retrieve data from database', 500);
    }
    mysqli_close($connect);
} else {
    $response = setError('Unable to connect to database', 500);
}
echo json_encode($response);
