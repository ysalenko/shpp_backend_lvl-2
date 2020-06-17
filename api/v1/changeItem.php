<?php
require_once('../src/headers_v1.php');
require_once('../src/ProcessingFiles.php');
$request = ProcessingFiles::getRequestJsonData();
header('Content-type');
$result = ['ok' => false];
$db_file = fopen(ProcessingFiles::$DB_FILE_NAME, "r+");
if (flock($db_file, LOCK_EX)) {
    $todo_list_array = json_decode(stream_get_contents($db_file), true);
    for ($k = 0; $k < count($todo_list_array); $k++) {
        if ($todo_list_array[$k]['id'] == $request['id']) {
            $todo_list_array[$k]['checked'] = $request['checked'];
            $todo_list_array[$k]['text'] = $request['text'];
            $result['ok'] = true;
            break;
        }
    }
    ftruncate($db_file, 0); //reduce file size
    rewind($db_file);// set file pointer to 0
    fwrite($db_file, json_encode($todo_list_array));
    fflush($db_file);
    flock($db_file, LOCK_UN);
}
fclose($db_file);

echo json_encode($result);
