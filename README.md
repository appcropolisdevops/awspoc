# HIPAA POC - Secure Messaging

A PHP-based proof-of-concept for HIPAA-compliant secure messaging with Google OAuth authentication and SQLite storage.

## Stack

- PHP 8.2 (FPM)
- Nginx
- SQLite
- Google OAuth 2.0
- Docker / Docker Compose

## Quick Start

1. Copy `.env.example` to `.env` and fill in your Google OAuth credentials:

```bash
cp .env.example .env
```

2. Build and run:

```bash
docker-compose up --build
```

3. Open http://localhost:8080

## Features

- Google OAuth staff-only authentication
- Send and view messages (stored in SQLite)
- CSRF protection on all forms
- Audit logging (login, logout, message create/delete)
- XSS protection via output escaping
- Security headers (CSP, X-Frame-Options, etc.)

## Project Structure

```
src/
  public/         # Web root (index, dashboard, login, etc.)
    assets/       # CSS
  includes/       # PHP classes (Auth, Database, Message, AuditLog)
docker/
  nginx/          # Nginx config
  php/            # PHP Dockerfile
docker-compose.yml
.env.example
```

## Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. Create OAuth 2.0 Client ID (Web application)
3. Set authorized redirect URI to: `http://localhost:8080/login.php`
4. Copy Client ID and Secret to `.env`
