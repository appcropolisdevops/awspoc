<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth;
use App\AuditLog;

$user = Auth::requireLogin();
$logs = AuditLog::getRecent(100);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Secure Messaging</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Secure Messaging</div>
        <div class="nav-user">
            <span><?= htmlspecialchars($user['name']) ?></span>
            <a href="/dashboard.php" class="btn btn-sm">Dashboard</a>
            <a href="/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Audit Log</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="5" class="text-muted">No audit entries yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['created_at']) ?></td>
                                <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
                                <td><span class="badge"><?= htmlspecialchars($log['action']) ?></span></td>
                                <td><?= htmlspecialchars($log['details'] ?? '') ?></td>
                                <td><?= htmlspecialchars($log['ip_address'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
