<?php

require_once __DIR__ . '/../vendor/autoload.php';

/* ✅ Create Google Client */
$client = new Google_Client();

/* 🔐 Load credentials from ENV (NOT hardcoded) */
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));

/* 🔁 Redirect after login */
$client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));

/* 📧 Permissions */
$client->addScope("email");
$client->addScope("profile");

/* 🔒 Extra security */
$client->setAccessType('offline'); // refresh token support
$client->setPrompt('select_account'); // forces account selection