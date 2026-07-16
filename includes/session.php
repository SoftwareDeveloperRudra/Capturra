<?php

function secureSessionStart() {

    // ✅ Prevent multiple session starts
    if (session_status() == PHP_SESSION_NONE) {

        $session_name = 'capturra_secure_session';

        $secure = isset($_SERVER['HTTPS']); // HTTPS check
        $httponly = true;

        // Force cookies only
        ini_set('session.use_only_cookies', 1);

        $cookieParams = session_get_cookie_params();

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => $cookieParams["path"],
            'domain'   => $cookieParams["domain"],
            'secure'   => $secure,
            'httponly' => $httponly,
            'samesite' => 'Strict'
        ]);

        session_name($session_name);
        session_start();
    }

    // ✅ Prevent session fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }

    // ✅ Session timeout (30 mins)
    $timeout = 1800;

    if (isset($_SESSION['LAST_ACTIVITY']) &&
        (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {

        session_unset();
        session_destroy();
        header("Location: /Capturra/login.php?timeout=1");
        exit();
    }

    $_SESSION['LAST_ACTIVITY'] = time();
}