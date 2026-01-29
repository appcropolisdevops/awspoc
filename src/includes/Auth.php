<?php

namespace App;

use League\OAuth2\Client\Provider\Google;

class Auth
{
    private Google $provider;

    public function __construct()
    {
        $this->provider = new Google([
            'clientId'     => getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => getenv('GOOGLE_REDIRECT_URI'),
        ]);
    }

    public function getAuthUrl(): string
    {
        $url = $this->provider->getAuthorizationUrl([
            'scope' => ['email', 'profile'],
        ]);
        $_SESSION['oauth2state'] = $this->provider->getState();
        return $url;
    }

    public function handleCallback(string $code, string $state): ?array
    {
        // Verify state to prevent CSRF
        if (empty($_SESSION['oauth2state']) || $state !== $_SESSION['oauth2state']) {
            unset($_SESSION['oauth2state']);
            error_log("OAuth state mismatch");
            return null;
        }
        unset($_SESSION['oauth2state']);

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $code,
            ]);

            $user = $this->provider->getResourceOwner($token);
            $userData = $user->toArray();

            return [
                'google_id' => $userData['sub'] ?? $user->getId(),
                'email'     => $userData['email'] ?? '',
                'name'      => $userData['name'] ?? '',
                'avatar'    => $userData['picture'] ?? '',
            ];
        } catch (\Exception $e) {
            error_log("Auth callback error: " . $e->getMessage());
            return null;
        }
    }

    public function findOrCreateUser(array $userData): array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$userData['google_id']]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, name = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$userData['name'], $userData['avatar'], $user['id']]);
            $user['name'] = $userData['name'];
            $user['avatar'] = $userData['avatar'];
        } else {
            $stmt = $db->prepare("INSERT INTO users (google_id, email, name, avatar) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $userData['google_id'],
                $userData['email'],
                $userData['name'],
                $userData['avatar'],
            ]);
            $user = [
                'id' => $db->lastInsertId(),
                'google_id' => $userData['google_id'],
                'email' => $userData['email'],
                'name' => $userData['name'],
                'avatar' => $userData['avatar'],
            ];
        }

        AuditLog::log($user['id'], 'LOGIN', 'User logged in via Google OAuth');

        return $user;
    }

    public static function requireLogin(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            header('Location: /login.php');
            exit;
        }

        return $_SESSION['user'];
    }

    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return !empty($_SESSION['user']);
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['user'])) {
            AuditLog::log($_SESSION['user']['id'], 'LOGOUT', 'User logged out');
        }

        session_destroy();
        header('Location: /');
        exit;
    }
}
