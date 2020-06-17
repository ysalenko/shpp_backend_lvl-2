<?php
require_once('DB_root.php');
$host = 'localhost';
$DBName = 'ToDoDB';
$user = 'JohnDoe';
$password = 'somePa$$w0rd';
$dataTblName = 'ToDos';

$conn = mysqli_connect($host, $rootLogin, $rootPassword);
if ($conn) {
    $resultOfDBCreation = mysqli_query($conn, "CREATE DATABASE $DBName DEFAULT CHARSET utf8;");
    if ($resultOfDBCreation) {
        mysqli_query($conn, "GRANT ALL PRIVILEGES ON $DBName.* TO '$user'@'localhost' IDENTIFIED BY '$password';");
        mysqli_query($conn, "USE $DBName;");
        $resultOfTblCreation = mysqli_query($conn,
            "CREATE TABLE $dataTblName(
                id INT(6) AUTO_INCREMENT PRIMARY KEY,
                text MEDIUMTEXT NOT NULL,
                checked TINYINT(1) default 0);");
    }
    mysqli_close($conn);
}
