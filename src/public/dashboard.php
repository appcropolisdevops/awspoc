<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth;
use App\Message;

$user = Auth::requireLogin();
$messages = Message::getAll();

$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Secure Messaging</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Secure Messaging</div>
        <div class="nav-user">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="avatar">
            <?php endif; ?>
            <span><?= htmlspecialchars($user['name']) ?></span>
            <a href="/audit.php" class="btn btn-sm">Audit Log</a>
            <a href="/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <?php if ($success === 'sent'): ?>
            <div class="alert alert-success">Message sent successfully.</div>
        <?php endif; ?>
        <?php if ($error === 'missing_fields'): ?>
            <div class="alert alert-error">Please fill in all fields.</div>
        <?php endif; ?>
        <?php if ($error === 'invalid_token'): ?>
            <div class="alert alert-error">Invalid request. Please try again.</div>
        <?php endif; ?>

        <div class="card">
            <h2>Send a Message</h2>
            <form action="/send.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required maxlength="255" placeholder="Message subject">
                </div>
                <div class="form-group">
                    <label for="body">Message</label>
                    <textarea id="body" name="body" required rows="4" placeholder="Type your message here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div class="card">
            <h2>Messages</h2>
            <?php if (empty($messages)): ?>
                <p class="text-muted">No messages yet.</p>
            <?php else: ?>
                <div class="message-list">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-item">
                            <div class="message-header">
                                <div class="message-author">
                                    <?php if (!empty($msg['author_avatar'])): ?>
                                        <img src="<?= htmlspecialchars($msg['author_avatar']) ?>" alt="" class="avatar-sm">
                                    <?php endif; ?>
                                    <strong><?= htmlspecialchars($msg['author_name']) ?></strong>
                                    <span class="text-muted">&lt;<?= htmlspecialchars($msg['author_email']) ?>&gt;</span>
                                </div>
                                <span class="message-date"><?= htmlspecialchars($msg['created_at']) ?></span>
                            </div>
                            <div class="message-subject"><?= htmlspecialchars($msg['subject']) ?></div>
                            <div class="message-body"><?= nl2br(htmlspecialchars($msg['body'])) ?></div>
                            <?php if ((int)$msg['user_id'] === (int)$user['id']): ?>
                                <form action="/delete.php" method="POST" class="inline-form">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="message_id" value="<?= (int)$msg['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
