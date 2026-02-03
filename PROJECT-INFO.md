# Project Information - TaxPlanner.app

**Developer**: Naeem Dosh
**Platform**: Fiverr
**Project Type**: HIPAA-Compliant Secure Messaging Application
**Deployment Date**: February 3, 2026
**Status**: ✅ Live and Operational

---

## 📋 Project Summary

### Application Details

| Property | Value |
|----------|-------|
| **Application Name** | TaxPlanner.app |
| **Live URL** | https://taxplanner.app |
| **Purpose** | HIPAA-compliant secure messaging for healthcare |
| **Version** | 1.0 |
| **Deployment Date** | February 3, 2026 |
| **Status** | Production - Operational |

### Technology Stack

| Layer | Technology | Version |
|-------|------------|---------|
| **Frontend** | HTML, CSS, JavaScript | - |
| **Web Server** | Nginx | Alpine (latest) |
| **Backend** | PHP-FPM | 8.2 |
| **Database** | SQLite | 3.x |
| **Container Platform** | Docker | Latest |
| **Operating System** | Amazon Linux | 2023 |
| **Infrastructure** | AWS (10+ services) | - |
| **IaC** | Terraform | 1.x |
| **Authentication** | Google OAuth 2.0 | - |

---

## 🏗️ Infrastructure Details

### AWS Resources

**Region**: us-east-2 (Ohio)

| Resource Type | Identifier | Purpose |
|---------------|------------|---------|
| **VPC** | vpc-0dbc4f0061da966f5 | Network isolation |
| **Public Subnet 1** | subnet-067dbc5fe85a9fd39 | AZ: us-east-2a |
| **Public Subnet 2** | subnet-08d44016cb5d8f80d | AZ: us-east-2b |
| **Private Subnet 1** | subnet-00e16d0504e61cf41 | AZ: us-east-2a |
| **Private Subnet 2** | subnet-0a876d728ca4826fe | AZ: us-east-2b |
| **EC2 Instance** | i-04c7660dd799eda07 | t3.small |
| **Load Balancer** | hipaa-poc-alb | Application LB |
| **Target Group** | hipaa-poc-tg | Health checks |
| **S3 Bucket** | hipaa-poc-backups-730543776652 | Encrypted backups |
| **SSL Certificate** | arn:aws:acm:us-east-2:... | ACM certificate |
| **Secrets** | hipaa-poc/app-secrets | OAuth credentials |

### Network Configuration

```
VPC: 10.0.0.0/16
├─ Public Subnets:
│  ├─ 10.0.0.0/24 (us-east-2a) - ALB, NAT
│  └─ 10.0.1.0/24 (us-east-2b) - ALB HA
│
└─ Private Subnets:
   ├─ 10.0.10.0/24 (us-east-2a) - EC2 Application
   └─ 10.0.11.0/24 (us-east-2b) - Reserved
```

### DNS Configuration

```
Domain: taxplanner.app
CNAME → hipaa-poc-alb-235408071.us-east-2.elb.amazonaws.com

SSL Certificate: Validated
Status: ISSUED
```

---

## 📂 Project Structure

```
awspoc/
├── README.md                       # Project overview
├── PROJECT-INFO.md                 # This file
├── DOCUMENTATION_SUMMARY.md        # Doc index
│
├── docs/                           # 📚 Documentation
│   ├── README.md                   # Navigation hub
│   ├── QUICK-START.md             # 5-min setup
│   ├── CLIENT-GUIDE.md            # Complete guide
│   ├── ARCHITECTURE.md            # Architecture details
│   ├── TERRAFORM-GUIDE.md         # Terraform manual
│   ├── PDF-GENERATION-GUIDE.md    # PDF creation
│   │
│   ├── technical/                 # Admin docs
│   │   ├── INFRASTRUCTURE.md
│   │   ├── OPERATIONS.md
│   │   └── SECURITY.md
│   │
│   └── archive/                   # Old docs
│       ├── DEPLOYMENT_COMPLETE.md
│       └── DEPLOYMENT_STATUS.md
│
├── src/                           # 💻 Application code
│   ├── public/                    # Web root
│   │   ├── index.php             # Landing page
│   │   ├── login.php             # OAuth callback
│   │   ├── logout.php            # Logout
│   │   ├── dashboard.php         # Main app
│   │   ├── send.php              # Send message
│   │   ├── delete.php            # Delete message
│   │   ├── audit.php             # Audit logs
│   │   └── assets/
│   │       └── style.css
│   │
│   ├── includes/                  # PHP classes
│   │   ├── Auth.php              # OAuth integration
│   │   ├── Database.php          # SQLite connection
│   │   ├── Message.php           # Message CRUD
│   │   └── AuditLog.php          # Audit logging
│   │
│   ├── composer.json
│   ├── composer.lock
│   └── vendor/                    # Dependencies
│
├── docker/                        # 🐳 Container configs
│   ├── nginx/
│   │   └── default.conf          # Nginx configuration
│   └── php/
│       └── Dockerfile            # PHP-FPM image
│
├── terraform/                     # 🏗️ Infrastructure
│   ├── main.tf                   # Provider, backend
│   ├── variables.tf              # Input variables
│   ├── outputs.tf                # Output values
│   ├── vpc.tf                    # VPC, subnets
│   ├── security.tf               # Security groups
│   ├── alb.tf                    # Load balancer
│   ├── ec2.tf                    # EC2 instance
│   ├── iam.tf                    # IAM roles
│   ├── s3.tf                     # S3 bucket
│   ├── secrets.tf                # Secrets Manager
│   ├── user_data.sh              # Bootstrap script
│   ├── terraform.tfvars          # Variables (gitignored)
│   ├── terraform.tfvars.example  # Template
│   │
│   └── backend-setup/            # State backend
│       └── main.tf
│
├── .env                          # Environment vars (gitignored)
├── .env.example                  # Template
├── .gitignore
└── docker-compose.yml            # Local development
```

---

## 🔑 Access Information

### Application Access

**URL**: https://taxplanner.app
**Login**: Google OAuth
**Admin**: Via Google Workspace settings

### AWS Access

**Region**: us-east-2 (Ohio)
**Account ID**: 730543776652

```bash
# Configure AWS CLI
aws configure
# Then use region: us-east-2
```

### Server Access (No SSH)

```bash
# Connect via AWS Systems Manager
aws ssm start-session \
  --target i-04c7660dd799eda07 \
  --region us-east-2
```

### Application Logs

```bash
# CloudWatch Logs
aws logs tail /hipaa-poc/application --follow --region us-east-2

# Or via SSM
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2
# Then: docker logs app-nginx
# And: docker logs app-php
```

---

## 🛠️ Development Workflow

### Local Development

```bash
# Clone repository
git clone https://github.com/appcropolisdevops/awspoc.git
cd awspoc

# Copy environment file
cp .env.example .env
nano .env  # Add Google OAuth credentials

# Install PHP dependencies
docker run --rm -v "$(pwd)/src:/app" -w /app composer:latest install

# Start local environment
docker compose up -d

# Access application
open http://localhost:8080
```

### Terraform Deployment

```bash
# Navigate to terraform directory
cd terraform

# Initialize
terraform init

# Plan changes
terraform plan -out=tfplan

# Apply
terraform apply tfplan

# View outputs
terraform output
```

### Update Application

```bash
# Connect to server
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2

# Pull latest code
cd /app
sudo git pull

# Restart containers
docker restart app-php app-nginx

# Verify
docker ps
curl http://localhost
```

---

## 📊 Monitoring & Maintenance

### Health Checks

```bash
# Application status
curl -I https://taxplanner.app

# Target health
aws elbv2 describe-target-health \
  --target-group-arn arn:aws:elasticloadbalancing:us-east-2:730543776652:targetgroup/hipaa-poc-tg/570d0f3e371009ef \
  --region us-east-2

# EC2 status
aws ec2 describe-instance-status \
  --instance-ids i-04c7660dd799eda07 \
  --region us-east-2
```

### Backups

**Schedule**: Daily at 2:00 AM UTC
**Location**: S3 bucket (encrypted)
**Retention**: 30 days

```bash
# List backups
aws s3 ls s3://hipaa-poc-backups-730543776652/backups/

# Manual backup
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2
# Then: sudo /usr/local/bin/backup-db.sh
```

### Database

**Type**: SQLite
**Location**: /data/db/app.sqlite (encrypted EBS)
**Size**: ~28KB (current)
**Encryption**: AES-256

```bash
# Check database
docker exec app-php ls -lh /var/www/data/app.sqlite

# Backup database
docker exec app-php sqlite3 /var/www/data/app.sqlite .dump > backup.sql
```

---

## 💰 Cost Breakdown

### Monthly Costs

| Resource | Specification | Cost/Month |
|----------|---------------|------------|
| EC2 (t3.small) | 2 vCPU, 2GB RAM | $15.18 |
| EBS Volumes | 60 GB gp3 | $4.80 |
| Application Load Balancer | Always-on | $16.20 |
| Data Transfer | ~50 GB | $4.50 |
| S3 Storage | ~5 GB backups | $0.12 |
| Secrets Manager | 1 secret | $0.40 |
| CloudWatch Logs | ~1 GB logs | $0.50 |
| Other Services | Various | $1.00 |
| **TOTAL** | | **~$42.70/month** |

### Cost Optimization Options

- Reserved Instances: Save 40%
- Reduce backup retention: Save $0.05-0.10
- Spot instances: Not recommended for production

---

## 🔒 Security Features

### Implemented Security

✅ **Authentication**: Google OAuth 2.0 (no passwords)
✅ **Encryption in Transit**: TLS 1.3 via ALB
✅ **Encryption at Rest**: EBS AES-256, S3 AES-256
✅ **Network Isolation**: Private subnets, security groups
✅ **Access Control**: IAM roles, least privilege
✅ **No SSH**: SSM Session Manager only
✅ **Audit Logging**: CloudWatch + application logs
✅ **CSRF Protection**: Token-based
✅ **XSS Prevention**: Input sanitization
✅ **Session Security**: Secure cookies, timeouts
✅ **Automated Backups**: Daily encrypted backups
✅ **IMDSv2**: Required on EC2
✅ **Security Headers**: CSP, X-Frame-Options, etc.

### HIPAA Compliance

✅ BAA-eligible AWS services
✅ Encryption at rest and in transit
✅ Access controls and authentication
✅ Audit logging
✅ Automatic backups
✅ Network isolation
✅ Regular security updates

---

## 📚 Documentation

### Complete Documentation Set

1. **[QUICK-START.md](docs/QUICK-START.md)** - 5-minute setup guide
2. **[CLIENT-GUIDE.md](docs/CLIENT-GUIDE.md)** - Complete user manual
3. **[ARCHITECTURE.md](docs/ARCHITECTURE.md)** - System architecture
4. **[TERRAFORM-GUIDE.md](docs/TERRAFORM-GUIDE.md)** - Infrastructure guide
5. **[PDF-GENERATION-GUIDE.md](docs/PDF-GENERATION-GUIDE.md)** - Create PDFs

**Technical Docs**:
- [INFRASTRUCTURE.md](docs/technical/INFRASTRUCTURE.md)
- [OPERATIONS.md](docs/technical/OPERATIONS.md)
- [SECURITY.md](docs/technical/SECURITY.md)

### Generate PDFs

```bash
# Install pandoc
sudo apt-get install pandoc texlive-xetex

# Generate PDFs
cd docs
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf --toc
pandoc TERRAFORM-GUIDE.md -o TERRAFORM-GUIDE.pdf --toc
pandoc CLIENT-GUIDE.md -o CLIENT-GUIDE.pdf --toc

# See PDF-GENERATION-GUIDE.md for details
```

---

## 🚀 Deployment Timeline

### Initial Deployment

| Date | Milestone | Status |
|------|-----------|--------|
| Feb 2, 2026 | Infrastructure deployed | ✅ |
| Feb 2, 2026 | DNS configured | ✅ |
| Feb 2, 2026 | SSL certificate issued | ✅ |
| Feb 2, 2026 | Application deployed | ✅ |
| Feb 3, 2026 | Database permissions fixed | ✅ |
| Feb 3, 2026 | Health checks passing | ✅ |
| Feb 3, 2026 | Documentation completed | ✅ |
| Feb 3, 2026 | **Production launch** | ✅ |

---

## 🐛 Troubleshooting Quick Reference

### Application Not Loading

```bash
# Check target health
aws elbv2 describe-target-health --target-group-arn <ARN> --region us-east-2

# Connect to server
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2

# Check containers
docker ps
docker logs app-nginx
docker logs app-php

# Restart if needed
docker restart app-nginx app-php
```

### Database Issues

```bash
# Check database file
docker exec app-php ls -lh /var/www/data/

# Check permissions
docker exec app-php ls -la /var/www/data/

# Fix permissions
docker exec app-php chown -R www-data:www-data /var/www/data/
```

### OAuth Login Issues

1. Verify redirect URI in Google Console:
   ```
   https://taxplanner.app/login.php
   ```

2. Check if user is authorized (Google Console → OAuth consent screen → Test users)

3. Verify secrets in AWS Secrets Manager

---

## 📞 Contact & Support

### Developer Information

**Name**: Naeem Dosh
**Platform**: Fiverr
**Project**: TaxPlanner.app HIPAA POC
**Specialization**: AWS, Terraform, PHP, HIPAA Compliance

### Project Details

**Repository**: https://github.com/appcropolisdevops/awspoc
**Live Application**: https://taxplanner.app
**Documentation**: See `docs/` folder

### Support Resources

- **Quick Start**: docs/QUICK-START.md
- **User Guide**: docs/CLIENT-GUIDE.md
- **Technical**: docs/technical/
- **Architecture**: docs/ARCHITECTURE.md
- **Terraform**: docs/TERRAFORM-GUIDE.md

---

## ✅ Project Deliverables

### Completed Deliverables

- [x] Full-stack PHP application with Google OAuth
- [x] Docker containerization (Nginx + PHP-FPM)
- [x] Complete AWS infrastructure (Terraform)
- [x] VPC with public/private subnets
- [x] Application Load Balancer with SSL
- [x] Encrypted storage (EBS + S3)
- [x] Automated daily backups
- [x] IAM roles and security groups
- [x] CloudWatch logging
- [x] SSM access (no SSH)
- [x] HIPAA-compliant architecture
- [x] Comprehensive documentation (8 docs)
- [x] Client user guides
- [x] Technical administration guides
- [x] PDF generation instructions
- [x] Troubleshooting guides
- [x] Live production deployment

### Documentation Deliverables

- [x] README.md - Project overview
- [x] QUICK-START.md - 5-minute setup
- [x] CLIENT-GUIDE.md - Complete user manual
- [x] ARCHITECTURE.md - System architecture
- [x] TERRAFORM-GUIDE.md - Infrastructure guide
- [x] PDF-GENERATION-GUIDE.md - PDF creation
- [x] INFRASTRUCTURE.md - AWS details
- [x] OPERATIONS.md - Admin procedures
- [x] SECURITY.md - Security controls
- [x] PROJECT-INFO.md - This file
- [x] DOCUMENTATION_SUMMARY.md - Doc index

---

## 🎯 Future Enhancements (Optional)

### Potential Improvements

**Scalability**:
- [ ] Add Auto Scaling Group
- [ ] Migrate to RDS (PostgreSQL)
- [ ] Add ElastiCache (Redis)
- [ ] Implement CloudFront CDN

**Features**:
- [ ] Two-factor authentication
- [ ] File attachments
- [ ] Message search
- [ ] Email notifications
- [ ] Mobile app

**Monitoring**:
- [ ] CloudWatch alarms
- [ ] SNS notifications
- [ ] Dashboard (Grafana)
- [ ] Performance monitoring (X-Ray)

**Security**:
- [ ] WAF (Web Application Firewall)
- [ ] GuardDuty
- [ ] Security Hub
- [ ] Automated security scans

---

## 📝 Change Log

### Version 1.0 (February 3, 2026)

**Initial Release**:
- Production deployment completed
- All features working
- Documentation completed
- SSL certificate validated
- Database permissions fixed
- Health checks passing
- Backups operational

**Infrastructure**:
- VPC with 4 subnets
- EC2 t3.small instance
- Application Load Balancer
- S3 encrypted backups
- Secrets Manager
- CloudWatch Logs

**Application**:
- Google OAuth authentication
- Secure messaging
- Audit logging
- CSRF protection
- XSS prevention
- Session management

---

## 🏆 Project Statistics

**Lines of Code**:
- PHP: ~800 lines
- Terraform: ~600 lines
- Docker configs: ~50 lines
- Documentation: ~5000 lines

**Documentation Pages**: ~60 pages
**AWS Resources**: 25+ resources
**Development Time**: ~40 hours
**Documentation Time**: ~8 hours

---

## 📜 License

**License**: Proprietary - All rights reserved
**Client**: TaxPlanner.app
**Developer**: Naeem Dosh
**Year**: 2026

---

## 🎉 Project Status

**Status**: ✅ **COMPLETE AND OPERATIONAL**

Application is live, all features working, documentation complete, and ready for client use!

**Application URL**: https://taxplanner.app
**Deployment Date**: February 3, 2026
**Version**: 1.0
**Developer**: Naeem Dosh (Fiverr)

---

**End of Project Information**
