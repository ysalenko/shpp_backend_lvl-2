<?php
require('../src/headers.php');
require('../src/ProcessingFiles.php');

$id = ProcessingFiles::getRequestJsonData()['id'];
$result = ['ok' => false];
$db_file = fopen(ProcessingFiles::$DB_FILE_NAME, "r+");
if (flock($db_file, LOCK_EX)) {
    $todo_list_array = json_decode(stream_get_contents($db_file), true);
    for ($k = 0; $k < count($todo_list_array); $k++) {
        if ($todo_list_array[$k]['id'] == $id) {
            unset($todo_list_array[$k]);
            $result['ok'] = true;
            ProcessingFiles::writeContentToFile($db_file, json_encode($todo_list_array));
            break;
        }
    }
} else fclose($db_file);

echo json_encode($result);
