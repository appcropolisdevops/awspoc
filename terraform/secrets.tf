# Secrets Manager for Application Secrets (HIPAA-eligible)
resource "aws_secretsmanager_secret" "app_secrets" {
  name                    = "${var.project_name}/app-secrets"
  description             = "Application secrets for HIPAA POC"
  recovery_window_in_days = 7

  tags = {
    Name = "${var.project_name}-secrets"
  }
}

resource "aws_secretsmanager_secret_version" "app_secrets" {
  secret_id = aws_secretsmanager_secret.app_secrets.id

  secret_string = jsonencode({
    GOOGLE_CLIENT_ID     = var.google_client_id
    GOOGLE_CLIENT_SECRET = var.google_client_secret
    APP_SECRET           = var.app_secret != "" ? var.app_secret : random_password.app_secret.result
    DB_ENCRYPTION_KEY    = random_password.db_key.result
  })
}

# Generate random app secret if not provided
resource "random_password" "app_secret" {
  length  = 64
  special = false
}

# Generate database encryption key for SQLCipher
resource "random_password" "db_key" {
  length  = 64
  special = false
}
