# HIPAA POC - Secure Messaging

A PHP-based proof-of-concept for HIPAA-compliant secure messaging with Google OAuth authentication and SQLite storage, deployed on AWS with BAA-eligible services.

## Architecture

```
                    ┌─────────────────────────────────────────────────────────┐
                    │                        AWS VPC                           │
                    │                     (10.0.0.0/16)                        │
                    │                                                          │
    Internet        │   ┌─────────────────┐    ┌─────────────────┐            │
        │           │   │  Public Subnet  │    │  Public Subnet  │            │
        │           │   │   10.0.0.0/24   │    │   10.0.1.0/24   │            │
        ▼           │   │                 │    │                 │            │
   ┌─────────┐      │   │  ┌───────────┐  │    │                 │            │
   │   ALB   │◄─────┼───┼──│    NAT    │  │    │                 │            │
   │ (HTTPS) │      │   │  │  Gateway  │  │    │                 │            │
   └────┬────┘      │   │  └─────┬─────┘  │    │                 │            │
        │           │   └────────┼────────┘    └─────────────────┘            │
        │           │            │                                             │
        │           │   ┌────────┼────────┐    ┌─────────────────┐            │
        │           │   │ Private│Subnet  │    │  Private Subnet │            │
        │           │   │  10.0.10.0/24   │    │   10.0.11.0/24  │            │
        │           │   │        │        │    │                 │            │
        │           │   │  ┌─────▼─────┐  │    │                 │            │
        └───────────┼───┼──►    EC2    │  │    │                 │            │
                    │   │  │  (Docker) │  │    │                 │            │
                    │   │  │ PHP+Nginx │  │    │                 │            │
                    │   │  └─────┬─────┘  │    │                 │            │
                    │   │        │        │    │                 │            │
                    │   └────────┼────────┘    └─────────────────┘            │
                    │            │                                             │
                    │            ▼                                             │
                    │   ┌─────────────────┐    ┌─────────────────┐            │
                    │   │   Encrypted     │    │  S3 (Backups)   │            │
                    │   │   EBS Volume    │    │   Encrypted     │            │
                    │   │   (SQLite DB)   │    │                 │            │
                    │   └─────────────────┘    └─────────────────┘            │
                    │                                                          │
                    └─────────────────────────────────────────────────────────┘
```

## Stack

| Component | Technology |
|-----------|------------|
| Application | PHP 8.2 (FPM) |
| Web Server | Nginx |
| Database | SQLite (encrypted EBS) |
| Authentication | Google OAuth 2.0 |
| Container | Docker / Docker Compose |
| Cloud | AWS (HIPAA BAA-eligible) |
| IaC | Terraform |

## HIPAA-Eligible AWS Services Used

- EC2 (Elastic Compute Cloud)
- EBS (Elastic Block Store) - encrypted
- ALB (Application Load Balancer)
- ACM (AWS Certificate Manager)
- S3 (Simple Storage Service) - encrypted
- Secrets Manager
- VPC (Virtual Private Cloud)
- CloudWatch Logs
- Systems Manager (SSM)
- DynamoDB (for Terraform state locking)

## Quick Start (Local Development)

1. Clone the repository:
```bash
git clone https://github.com/appcropolisdevops/awspoc.git
cd awspoc
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Add your Google OAuth credentials to `.env`

4. Install dependencies:
```bash
docker run --rm -v "$(pwd)/src:/app" -w /app composer:latest install
```

5. Start the application:
```bash
docker compose up -d
```

6. Open http://localhost:8080

## AWS Deployment

### Prerequisites

- AWS Account with BAA signed
- AWS CLI configured
- Terraform >= 1.0
- Domain name (for SSL certificate)

### Deploy Infrastructure

1. Navigate to terraform directory:
```bash
cd terraform
```

2. Create `terraform.tfvars`:
```hcl
aws_region           = "us-east-2"
project_name         = "hipaa-poc"
domain_name          = "yourdomain.com"
google_client_id     = "your-client-id.apps.googleusercontent.com"
google_client_secret = "your-client-secret"
```

3. Initialize and apply:
```bash
terraform init
terraform apply
```

4. Add DNS records (shown in terraform output):
   - ACM validation CNAME record
   - Domain CNAME pointing to ALB

5. After DNS propagation, run again to create HTTPS listener:
```bash
terraform apply
```

### Connect to EC2

No SSH required - use SSM Session Manager:
```bash
aws ssm start-session --target <instance-id> --region us-east-2
```

## Features

- **Google OAuth** - Staff-only authentication with Google Workspace
- **Secure Messaging** - Send/view/delete messages
- **Audit Logging** - All actions logged (login, logout, CRUD)
- **CSRF Protection** - Token-based form protection
- **XSS Protection** - Output escaping on all user data
- **Security Headers** - CSP, X-Frame-Options, etc.
- **Encrypted Storage** - EBS and S3 encryption at rest
- **Encrypted Transit** - TLS 1.3 via ALB
- **No SSH** - SSM Session Manager for access
- **Daily Backups** - Automated to encrypted S3

## Project Structure

```
awspoc/
├── src/
│   ├── public/              # Web root
│   │   ├── index.php        # Landing page
│   │   ├── login.php        # OAuth callback
│   │   ├── logout.php       # Session destroy
│   │   ├── dashboard.php    # Main UI
│   │   ├── send.php         # Send message
│   │   ├── delete.php       # Delete message
│   │   ├── audit.php        # Audit log viewer
│   │   └── assets/
│   │       └── style.css
│   ├── includes/            # PHP classes
│   │   ├── Auth.php         # OAuth handling
│   │   ├── Database.php     # SQLite connection
│   │   ├── Message.php      # Message CRUD
│   │   └── AuditLog.php     # Audit logging
│   ├── composer.json
│   └── composer.lock
├── docker/
│   ├── nginx/
│   │   └── default.conf     # Nginx config
│   └── php/
│       └── Dockerfile       # PHP-FPM image
├── terraform/
│   ├── main.tf              # Provider & backend
│   ├── variables.tf         # Input variables
│   ├── vpc.tf               # VPC, subnets, NAT
│   ├── security.tf          # Security groups
│   ├── alb.tf               # ALB, ACM, listeners
│   ├── ec2.tf               # EC2 instance
│   ├── iam.tf               # IAM roles & policies
│   ├── s3.tf                # Backup bucket
│   ├── secrets.tf           # Secrets Manager
│   ├── outputs.tf           # Output values
│   ├── user_data.sh         # EC2 bootstrap
│   └── terraform.tfvars.example
├── docs/
│   ├── INFRASTRUCTURE.md    # AWS architecture details
│   ├── SECURITY.md          # Security controls
│   └── OPERATIONS.md        # Operational procedures
├── docker-compose.yml       # Local development
├── .env.example             # Environment template
├── .gitignore
└── README.md
```

## Security Controls

| Control | Implementation |
|---------|----------------|
| Authentication | Google OAuth with staff-only access |
| Authorization | Session-based with CSRF tokens |
| Encryption at Rest | EBS encryption, S3 SSE-S3 |
| Encryption in Transit | TLS 1.3 via ALB |
| Network Isolation | Private subnet, no direct internet |
| Access Control | IAM roles, Security Groups |
| Audit Logging | Application + CloudWatch Logs |
| Secrets Management | AWS Secrets Manager |
| Backup | Daily encrypted backups to S3 |

## Environment Variables

| Variable | Description |
|----------|-------------|
| `GOOGLE_CLIENT_ID` | Google OAuth Client ID |
| `GOOGLE_CLIENT_SECRET` | Google OAuth Client Secret |
| `GOOGLE_REDIRECT_URI` | OAuth callback URL |
| `APP_SECRET` | Application secret for sessions |
| `DB_PATH` | SQLite database path |

## Terraform State

State is stored remotely in S3 with DynamoDB locking:
- Bucket: `hipaa-poc-tfstate-<account-id>`
- Table: `hipaa-poc-tfstate-locks`

## Cost Estimate

| Resource | Monthly Cost |
|----------|-------------|
| EC2 t3.small | ~$15 |
| NAT Gateway | ~$35 |
| ALB | ~$20 |
| S3 | ~$1 |
| Other | ~$5 |
| **Total** | **~$75-80** |

## License

Proprietary - All rights reserved.

## Support

For issues, contact the DevOps team.
