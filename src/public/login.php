<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth;

session_start();

$auth = new Auth();

// If returning from Google with a code
if (isset($_GET['code'])) {
    $state = $_GET['state'] ?? '';
    $userData = $auth->handleCallback($_GET['code'], $state);

    if ($userData) {
        $user = $auth->findOrCreateUser($userData);
        $_SESSION['user'] = $user;
        header('Location: /dashboard.php');
        exit;
    }

    // Auth failed
    header('Location: /?error=auth_failed');
    exit;
}

// Redirect to Google OAuth
$authUrl = $auth->getAuthUrl();
header("Location: $authUrl");
exit;
