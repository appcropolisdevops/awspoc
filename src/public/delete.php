<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth;
use App\Message;

$user = Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard.php');
    exit;
}

// CSRF check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    header('Location: /dashboard.php?error=invalid_token');
    exit;
}

$messageId = (int) ($_POST['message_id'] ?? 0);

if ($messageId > 0) {
    Message::delete($messageId, (int) $user['id']);
}

// Regenerate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

header('Location: /dashboard.php');
exit;
