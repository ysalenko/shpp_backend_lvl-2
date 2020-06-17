<?php
require_once('../src/ProcessingFiles.php');

$toDoList['items'] = [];
$dbFile = fopen(ProcessingFiles::$DB_FILE_NAME, "r");
if (flock($dbFile, LOCK_EX)) {
    $toDoList['items'] = json_decode(stream_get_contents($dbFile), true);
    flock($dbFile, LOCK_UN);
}
fclose($dbFile);
echo json_encode($toDoList);
