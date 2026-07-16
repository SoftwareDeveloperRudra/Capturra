<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

/* 🔐 Security Headers */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

/* Helper function to send JSON */
function apiResponse($success, $message, $data = null) {
    header("Content-Type: application/json");
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

/* 🔐 Session Hijacking Protection */
function validateSession() {
    if (!isset($_SESSION['ip']) || !isset($_SESSION['user_agent'])) {
        return false;
    }

    if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
        $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        
        session_unset();
        session_destroy();
        return false;
    }

    return true;
}

/* Require login */
function requireLogin($isApi = false) {

    if (!isset($_SESSION['user_id']) || !validateSession()) {

        if ($isApi) {
            apiResponse(false, "Not logged in");
        } else {
            header("Location: /Capturra/public/login.html");
            exit();
        }
    }
}

/* Require role */
function requireRole($role, $isApi = false) {
    requireLogin($isApi);

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {

        if ($isApi) {
            apiResponse(false, "Access denied");
        } else {
            header("Location: /Capturra/public/unauthorized.html");
            exit();
        }
    }
}
// 🔐 Session Hijack Protection
if (isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: /Capturra/public/login.html");
    exit();
}

if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("Location: /Capturra/public/login.html");
    exit();
}