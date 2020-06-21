<?php
require('../src/headers.php');
require('../src/ProcessingFiles.php');

$newToDoItem = ProcessingFiles::getRequestJsonData();
$newToDoItem['id'] = ProcessingFiles::getNextId();
$newToDoItem['checked'] = false;

$dbFile = fopen(ProcessingFiles::$DB_FILE_NAME, "r+");
if ($dbFile!=false && flock($dbFile, LOCK_EX)) {
    $toDoList = json_decode(stream_get_contents($dbFile), true);
    $toDoList[] = $newToDoItem;
    ProcessingFiles::writeContentToFile($dbFile, json_encode($toDoList));
} else {
    fclose($dbFile);
    http_response_code(500);
    die('Unable to get access to database');
}

echo json_encode(['id' => $newToDoItem['id']]);

