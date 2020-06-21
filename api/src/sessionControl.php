<?php
function sessionStart()
{
    session_start();
    if (!isset($_SESSION['userId'])) {
        $_SESSION = array();
        if (session_id() != "" || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 2592000, '/');
            unset($_COOKIE[session_name()]);
        }

        http_response_code(400);
        $response = ['error' => 'Unauthorised', 'ok' => false];
        echo json_encode($response);

        session_destroy();
        die();
    }
}

function setError(string $err, int $code) {
    http_response_code($code);
    return ['error' => $err, 'ok'=>false];
}

