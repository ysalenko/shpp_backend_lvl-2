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
    if($resultOfDBCreation) {
        $createUser = addDBNotRootUser($conn, $DBName, $user, $password);
    }
    $resultOfDataTblCreation = createDataTbl($conn, $dataTblName);
    $resultOfAddField = addFieldIntoTbl($conn, $dataTblName, 'userId');
    $resultOfUsersTblCreation = createUsersTbl($conn, $usersTblName);

    mysqli_close($conn);

}

function killScript(){
    http_response_code(500);
    echo '{"error": "Error using database"}';
    die();
}

function addDBNotRootUser($conn, $DBName, $user, $password): bool{
    $resultCrUser = mysqli_query($conn, "GRANT ALL PRIVILEGES ON $DBName.* TO '$user'@'localhost' IDENTIFIED BY '$password';");
    $resultChangeDB = mysqli_query($conn, "USE $DBName;");
    return $resultCrUser && $resultChangeDB;
}
function createDataTbl($connection, $dbName)
{
    return mysqli_query($connection,
        "CREATE TABLE IF NOT EXISTS $dbName(
                id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                text MEDIUMTEXT NOT NULL,
                checked TINYINT(1) default 0);");
}

function addFieldIntoTbl($connection, $tblName, $fieldName)
{
    $addFieldQuery = "ALTER TABLE $tblName ADD $fieldName INT;";
    return mysqli_query($connection, $addFieldQuery);
}

function createUsersTbl($connection, $tblName)
{
    return mysqli_query($connection,
        "CREATE TABLE IF NOT EXISTS $tblName(
                userId INT(6) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,  
                login TINYBLOB NOT NULL,
                password TINYBLOB NOT NULL);");
}

function getPDOConnection()
{
    return new PDO("mysql:host={$GLOBALS['host']};dbname={$GLOBALS['DBName']}",
        $GLOBALS['user'], $GLOBALS['password']);
}