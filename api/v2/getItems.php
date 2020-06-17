<?php
require('../src/headers_v2.php');
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    die('Unacceptable request method');
}

require_once('../src/DBConfig.php');
require_once('../src/ProcessingFiles.php');

$response = '{"error": 500}';

if($connect = mysqli_connect($host, $user, $password, $DBName)) {
    $select = "SELECT id, text, checked FROM $dataTblName";
    $result = mysqli_query($connect, $select);

    if ($result) {
        $items['items'] = [];
        $extractedRows = mysqli_num_rows($result);

        while ($row = mysqli_fetch_row($result)) {
            $items['items'][] = ['id' => (int)$row[0], 'text' => $row[1], 'checked' => (bool)$row[2]];
        }
        $response = json_encode($items);
    }
    mysqli_close($connect);
}
echo $response;
