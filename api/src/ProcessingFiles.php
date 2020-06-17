<?php

trait ProcessingFiles
{
    private static string $FILE_WITH_IDS = 'database/current_index.txt';
    public static string $DB_FILE_NAME = 'database/simple_DB.json';

    public static function getNextId(): int
    {
        $file = fopen(self::$FILE_WITH_IDS, "r+");
        if (flock($file, LOCK_EX)) {
            $nextId = stream_get_contents($file) + 1;
            self::writeContentToFile($file, $nextId);
            return $nextId;
        } else {
            fclose($file);
            http_response_code(500);
            die();
        }

    }

    public static function writeContentToFile($file, string $data): void {
        ftruncate($file, 0);
        rewind($file);
        fwrite($file, $data);
        fflush($file);
        flock($file, LOCK_UN);
        fclose($file);
    }

    public static function getRequestJsonData(): array
    {
        $jsonData = file_get_contents('php://input');
        return json_decode($jsonData, true);
    }

}