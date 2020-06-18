<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('Unacceptable request method');
}
//todo kill session
session_start();
$_SESSION = [];
unset($_COOKIE[session_name()]);
echo '{"ok": true}';
session_destroy();

