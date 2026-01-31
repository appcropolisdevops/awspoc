#!/bin/bash
set -e

# Log all output
exec > >(tee /var/log/user-data.log|logger -t user-data -s 2>/dev/console) 2>&1

echo "Starting user data script..."

# Update system
dnf update -y

# Install Docker
dnf install -y docker git jq

# Start Docker
systemctl enable docker
systemctl start docker

# Install Docker Compose
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# Install AWS CLI v2 (if not present)
if ! command -v aws &> /dev/null; then
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    unzip -q awscliv2.zip
    ./aws/install
    rm -rf aws awscliv2.zip
fi

# Mount data volume
mkdir -p /data
if ! mount | grep -q '/data'; then
    # Check if volume needs formatting
    if ! file -s /dev/xvdf | grep -q 'filesystem'; then
        mkfs -t xfs /dev/xvdf
    fi
    mount /dev/xvdf /data
    echo '/dev/xvdf /data xfs defaults,nofail 0 2' >> /etc/fstab
fi

# Set permissions
chown -R 1000:1000 /data

# Create app directory
mkdir -p /app
cd /app

# Clone application repository
git clone https://github.com/appcropolisdevops/awspoc.git .

# Get secrets from AWS Secrets Manager
SECRET_JSON=$(aws secretsmanager get-secret-value --secret-id "${secret_arn}" --region "${aws_region}" --query SecretString --output text)

# Create .env file
cat > .env << EOF
# Google OAuth Credentials
GOOGLE_CLIENT_ID=$(echo $SECRET_JSON | jq -r '.GOOGLE_CLIENT_ID')
GOOGLE_CLIENT_SECRET=$(echo $SECRET_JSON | jq -r '.GOOGLE_CLIENT_SECRET')
GOOGLE_REDIRECT_URI=https://${domain_name}/login.php

# App Secret
APP_SECRET=$(echo $SECRET_JSON | jq -r '.APP_SECRET')

# Database path (inside container)
DB_PATH=/var/www/data/app.sqlite

# Database encryption key (for SQLCipher - future use)
DB_ENCRYPTION_KEY=$(echo $SECRET_JSON | jq -r '.DB_ENCRYPTION_KEY')
EOF

# Update docker-compose for production
cat > docker-compose.prod.yml << 'PRODEOF'
services:
  php:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    volumes:
      - ./src:/var/www/html
      - /data/db:/var/www/data
    env_file:
      - .env
    networks:
      - app-network
    restart: always
    logging:
      driver: awslogs
      options:
        awslogs-region: ${aws_region}
        awslogs-group: /hipaa-poc/application
        awslogs-stream: php

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
    networks:
      - app-network
    restart: always
    logging:
      driver: awslogs
      options:
        awslogs-region: ${aws_region}
        awslogs-group: /hipaa-poc/application
        awslogs-stream: nginx

networks:
  app-network:
    driver: bridge
PRODEOF

# Create data directory
mkdir -p /data/db
chown -R 1000:1000 /data/db

# Install composer dependencies
docker run --rm -v "$(pwd)/src:/app" -w /app composer:latest install --no-dev --optimize-autoloader

# Start application
docker-compose -f docker-compose.prod.yml up -d --build

# Create backup script
cat > /usr/local/bin/backup-db.sh << 'BACKUPEOF'
#!/bin/bash
set -e
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="/tmp/backup_$TIMESTAMP.sqlite.gz"

# Backup database
sqlite3 /data/db/app.sqlite ".backup /tmp/backup_$TIMESTAMP.sqlite"
gzip /tmp/backup_$TIMESTAMP.sqlite

# Upload to S3 with server-side encryption
aws s3 cp "$BACKUP_FILE" "s3://${s3_bucket}/backups/db_$TIMESTAMP.sqlite.gz" --sse AES256

# Clean up
rm -f "$BACKUP_FILE"

echo "Backup completed: db_$TIMESTAMP.sqlite.gz"
BACKUPEOF

chmod +x /usr/local/bin/backup-db.sh

# Setup daily backup cron
echo "0 2 * * * root /usr/local/bin/backup-db.sh >> /var/log/backup.log 2>&1" > /etc/cron.d/db-backup

# Install and configure CloudWatch agent for audit logs
dnf install -y amazon-cloudwatch-agent

cat > /opt/aws/amazon-cloudwatch-agent/etc/amazon-cloudwatch-agent.json << 'CWEOF'
{
  "logs": {
    "logs_collected": {
      "files": {
        "collect_list": [
          {
            "file_path": "/var/log/user-data.log",
            "log_group_name": "/hipaa-poc/application",
            "log_stream_name": "user-data"
          }
        ]
      }
    }
  }
}
CWEOF

systemctl enable amazon-cloudwatch-agent
systemctl start amazon-cloudwatch-agent

echo "User data script completed successfully!"
