<?php
require_once('DB_root.php');
$host = 'localhost';
$DBName = 'ToDoDB';
$user = 'JohnDoe';
$password = 'somePa$$w0rd';
$dataTblName = 'ToDos';
$usersTblName = 'Users';

$conn = mysqli_connect($host, $rootLogin, $rootPassword);
if ($conn) {
    $resultOfDBCreation = mysqli_query($conn, "CREATE DATABASE $DBName DEFAULT CHARSET utf8;");
    if ($resultOfDBCreation) {
        mysqli_query($conn, "GRANT ALL PRIVILEGES ON $DBName.* TO '$user'@'localhost' IDENTIFIED BY '$password';");
        mysqli_query($conn, "USE $DBName;");
        $resultOfDataTblCreation = createDataTbl($conn, $dataTblName);
        $resultOfAddField = addFieldIntoTbl($conn, $dataTblName, 'userId');
        $resultOfUsersTblCreation = createUsersTbl($conn, $usersTblName);
    }
    mysqli_close($conn);
}
function createDataTbl($connection, $dbName) {
    return mysqli_query($connection,
        "CREATE TABLE IF NOT EXISTS $dbName(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                text MEDIUMTEXT NOT NULL,
                checked TINYINT(1) default 0);");
}

function addFieldIntoTbl($connection, $tblName, $fieldName){
    $addFieldQuery = "ALTER TABLE $tblName ADD $fieldName TEXT;";
    return mysqli_query($connection,$addFieldQuery);
}

function createUsersTbl($connection, $tblName) {
    return mysqli_query($connection,
        "CREATE TABLE IF NOT EXISTS $tblName(
                userId Int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,  
                login TINYBLOB NOT NULL,
                password TINYBLOB NOT NULL);");
}

function getPDOConnection() {
    return new PDO("mysql:host={$GLOBALS['host']};dbname={$GLOBALS['DBName']}",
    $GLOBALS['user'], $GLOBALS['password']);
}