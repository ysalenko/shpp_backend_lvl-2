<?php
require("../src/headers.php");
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}

session_start();
$_SESSION = array();
if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-2592000,'/');
    unset($_COOKIE[session_name()]);
}
echo json_encode(['ok'=> true]);
session_destroy();
