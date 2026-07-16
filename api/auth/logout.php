<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
session_unset();
session_destroy();

echo json_encode([
  "status" => true,
  "message" => "Logged out"
]);
