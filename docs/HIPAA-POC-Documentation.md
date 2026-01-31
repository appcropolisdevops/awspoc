# HIPAA-Compliant Secure Messaging POC
## Project Documentation

**Version:** 1.0
**Date:** January 31, 2026
**Prepared for:** Appcropolis
**Prepared by:** Naeem Dosh (DevOps Engineer)

---

# Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Project Overview](#2-project-overview)
3. [Architecture](#3-architecture)
4. [Technology Stack](#4-technology-stack)
5. [AWS Infrastructure](#5-aws-infrastructure)
6. [Security Controls](#6-security-controls)
7. [Application Features](#7-application-features)
8. [Deployment Guide](#8-deployment-guide)
9. [Operations Guide](#9-operations-guide)
10. [Cost Breakdown](#10-cost-breakdown)
11. [Next Steps for Production](#11-next-steps-for-production)
12. [Appendix](#12-appendix)

---

# 1. Executive Summary

This document describes the Proof of Concept (POC) implementation for a HIPAA-compliant secure messaging application. The POC demonstrates a secure environment for hosting Protected Health Information (PHI) using:

- **PHP-based web application** with Google OAuth authentication
- **SQLite database** with encrypted storage
- **Docker containers** for consistent deployment
- **AWS infrastructure** with HIPAA-eligible services
- **Comprehensive security controls** aligned with HIPAA requirements

**Key Achievements:**
- All AWS services used are HIPAA BAA-eligible
- End-to-end encryption (in transit and at rest)
- No direct internet exposure for compute resources
- Comprehensive audit logging
- Infrastructure as Code (Terraform) for reproducibility

---

# 2. Project Overview

## 2.1 Objectives

| Objective | Status |
|-----------|--------|
| Build secure, HIPAA-compliant environment | ✅ Complete |
| Implement Google OAuth for staff authentication | ✅ Complete |
| Set up encrypted database storage | ✅ Complete |
| Deploy on AWS with BAA-eligible services | ✅ Complete |
| Implement audit logging | ✅ Complete |
| Set up automated encrypted backups | ✅ Complete |
| Create Infrastructure as Code | ✅ Complete |
| Document all components | ✅ Complete |

## 2.2 Scope

**In Scope:**
- AWS infrastructure setup (VPC, EC2, ALB, S3)
- Docker containerization (PHP-FPM, Nginx)
- Google OAuth integration
- SQLite database with encrypted storage
- Audit logging for all user actions
- Encrypted backup system
- TLS/HTTPS via AWS ACM
- Documentation

**Out of Scope (for POC):**
- Multi-tenant architecture
- Production-grade database (MongoDB Atlas)
- High availability / Auto Scaling
- CI/CD pipeline
- Load testing

## 2.3 Timeline

| Milestone | Duration | Status |
|-----------|----------|--------|
| AWS Infrastructure | 5 days | ✅ Complete |
| Containerization | 5 days | ✅ Complete |
| Logging | 1 day | ✅ Complete |
| Backup & Documentation | 1 day | ✅ Complete |

---

# 3. Architecture

## 3.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                              INTERNET                                │
└─────────────────────────────────┬───────────────────────────────────┘
                                  │
                                  ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         AWS CLOUD (us-east-2)                        │
│  ┌───────────────────────────────────────────────────────────────┐  │
│  │                      VPC (10.0.0.0/16)                        │  │
│  │                                                               │  │
│  │   ┌─────────────────────────────────────────────────────┐    │  │
│  │   │              PUBLIC SUBNETS                          │    │  │
│  │   │   ┌─────────────┐         ┌─────────────┐           │    │  │
│  │   │   │     ALB     │         │     NAT     │           │    │  │
│  │   │   │   (HTTPS)   │         │   Gateway   │           │    │  │
│  │   │   └──────┬──────┘         └──────┬──────┘           │    │  │
│  │   └──────────┼───────────────────────┼──────────────────┘    │  │
│  │              │                       │                        │  │
│  │   ┌──────────┼───────────────────────┼──────────────────┐    │  │
│  │   │          │   PRIVATE SUBNETS     │                  │    │  │
│  │   │   ┌──────▼──────┐         ┌──────▼──────┐           │    │  │
│  │   │   │     EC2     │         │  Outbound   │           │    │  │
│  │   │   │   Docker    │─────────│   Traffic   │           │    │  │
│  │   │   │  PHP+Nginx  │         │             │           │    │  │
│  │   │   └──────┬──────┘         └─────────────┘           │    │  │
│  │   │          │                                          │    │  │
│  │   │   ┌──────▼──────┐                                   │    │  │
│  │   │   │  Encrypted  │                                   │    │  │
│  │   │   │ EBS Volume  │                                   │    │  │
│  │   │   │  (SQLite)   │                                   │    │  │
│  │   │   └─────────────┘                                   │    │  │
│  │   └─────────────────────────────────────────────────────┘    │  │
│  │                                                               │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                                                                      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │
│  │      S3      │  │   Secrets    │  │  CloudWatch  │               │
│  │   Backups    │  │   Manager    │  │     Logs     │               │
│  │  (Encrypted) │  │              │  │              │               │
│  └──────────────┘  └──────────────┘  └──────────────┘               │
│                                                                      │
└──────────────────────────────────────────────────────────────────────┘
```

## 3.2 Data Flow

1. **User Request** → HTTPS to ALB (port 443)
2. **ALB** → HTTP to EC2 (port 80, internal only)
3. **Nginx** → PHP-FPM (FastCGI)
4. **PHP Application** → SQLite database (encrypted EBS)
5. **Audit Logs** → CloudWatch Logs
6. **Backups** → S3 (encrypted)

## 3.3 Authentication Flow

```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│  User   │────►│   ALB   │────►│  Nginx  │────►│   PHP   │
└─────────┘     └─────────┘     └─────────┘     └────┬────┘
                                                      │
                                                      ▼
                                               ┌─────────────┐
                                               │   Google    │
                                               │   OAuth     │
                                               └──────┬──────┘
                                                      │
                                                      ▼
                                               ┌─────────────┐
                                               │  Session    │
                                               │  Created    │
                                               └─────────────┘
```

---

# 4. Technology Stack

## 4.1 Application Layer

| Component | Technology | Version |
|-----------|------------|---------|
| Runtime | PHP | 8.2 |
| Web Server | Nginx | Alpine (latest) |
| Database | SQLite | 3.x |
| Authentication | Google OAuth 2.0 | - |
| OAuth Library | league/oauth2-google | 4.x |

## 4.2 Infrastructure Layer

| Component | AWS Service | Notes |
|-----------|-------------|-------|
| Compute | EC2 (t3.small) | Amazon Linux 2023 |
| Load Balancer | ALB | Application Load Balancer |
| SSL/TLS | ACM | Managed certificates |
| Storage | EBS (gp3) | Encrypted |
| Object Storage | S3 | Encrypted, versioned |
| Secrets | Secrets Manager | For OAuth credentials |
| Logging | CloudWatch Logs | 90-365 day retention |
| Networking | VPC | Private subnets |
| Access | SSM Session Manager | No SSH required |

## 4.3 Container Stack

| Container | Image | Purpose |
|-----------|-------|---------|
| php | php:8.2-fpm (custom) | Application runtime |
| nginx | nginx:alpine | Reverse proxy |

## 4.4 Infrastructure as Code

| Tool | Purpose |
|------|---------|
| Terraform | AWS infrastructure provisioning |
| Docker Compose | Container orchestration |

---

# 5. AWS Infrastructure

## 5.1 Account Information

| Item | Value |
|------|-------|
| AWS Account ID | 730543776652 |
| Region | us-east-2 (Ohio) |
| BAA Status | ✅ Signed |

## 5.2 Network Configuration

### VPC

| Resource | CIDR/ID |
|----------|---------|
| VPC | 10.0.0.0/16 |
| VPC ID | vpc-0dbc4f0061da966f5 |

### Subnets

| Subnet | CIDR | AZ | Type |
|--------|------|-----|------|
| Public 1 | 10.0.0.0/24 | us-east-2a | Public |
| Public 2 | 10.0.1.0/24 | us-east-2b | Public |
| Private 1 | 10.0.10.0/24 | us-east-2a | Private |
| Private 2 | 10.0.11.0/24 | us-east-2b | Private |

### Connectivity

| Component | Purpose |
|-----------|---------|
| Internet Gateway | Public subnet internet access |
| NAT Gateway | Private subnet outbound access |
| VPC Endpoints | SSM, S3 access without internet |

## 5.3 Compute Resources

### EC2 Instance

| Setting | Value |
|---------|-------|
| Instance ID | i-0500bfb3b4ad44e24 |
| Instance Type | t3.small (2 vCPU, 2 GB RAM) |
| AMI | Amazon Linux 2023 |
| Subnet | Private Subnet 1 |
| Public IP | None (private subnet) |

### Storage

| Volume | Size | Type | Encrypted | Purpose |
|--------|------|------|-----------|---------|
| Root | 20 GB | gp3 | Yes | OS and application |
| Data | 10 GB | gp3 | Yes | SQLite database |

## 5.4 Load Balancer

### Application Load Balancer

| Setting | Value |
|---------|-------|
| Name | hipaa-poc-alb |
| Scheme | Internet-facing |
| DNS | hipaa-poc-alb-xxx.us-east-2.elb.amazonaws.com |

### Listeners

| Protocol | Port | Action |
|----------|------|--------|
| HTTP | 80 | Redirect to HTTPS |
| HTTPS | 443 | Forward to target group |

### SSL/TLS

| Setting | Value |
|---------|-------|
| Certificate | ACM (DNS validated) |
| Domain | taxplanner.app |
| TLS Policy | ELBSecurityPolicy-TLS13-1-2-2021-06 |

## 5.5 Storage

### S3 Buckets

| Bucket | Purpose | Encryption |
|--------|---------|------------|
| hipaa-poc-backups-730543776652 | Database backups | SSE-S3 |
| hipaa-poc-tfstate-730543776652 | Terraform state | SSE-S3 |

### Backup Lifecycle

| Age | Storage Class |
|-----|---------------|
| 0-30 days | Standard |
| 30-365 days | Glacier |
| >365 days | Deleted |

## 5.6 Security Groups

### ALB Security Group

| Direction | Port | Source | Purpose |
|-----------|------|--------|---------|
| Inbound | 443 | 0.0.0.0/0 | HTTPS traffic |
| Inbound | 80 | 0.0.0.0/0 | HTTP redirect |
| Outbound | All | 0.0.0.0/0 | Response traffic |

### EC2 Security Group

| Direction | Port | Source | Purpose |
|-----------|------|--------|---------|
| Inbound | 80 | ALB SG | HTTP from ALB |
| Outbound | All | 0.0.0.0/0 | Outbound access |

**Note:** No SSH port (22) is open. Access is via SSM Session Manager only.

## 5.7 IAM Configuration

### EC2 Instance Role

| Policy | Purpose |
|--------|---------|
| AmazonSSMManagedInstanceCore | SSM Session Manager |
| hipaa-poc-s3-backup | S3 backup access |
| hipaa-poc-secrets | Secrets Manager access |
| hipaa-poc-cloudwatch | CloudWatch Logs access |

---

# 6. Security Controls

## 6.1 HIPAA Alignment

| HIPAA Requirement | Implementation |
|-------------------|----------------|
| Access Control (164.312(a)) | Google OAuth, session management |
| Audit Controls (164.312(b)) | Application audit logging, CloudWatch |
| Integrity (164.312(c)) | CSRF protection, input validation |
| Transmission Security (164.312(e)) | TLS 1.3 via ALB |

## 6.2 Encryption

### At Rest

| Resource | Encryption Method |
|----------|-------------------|
| EBS Volumes | AES-256 (AWS managed) |
| S3 Buckets | SSE-S3 (AES-256) |
| Secrets Manager | AWS managed keys |

### In Transit

| Connection | Protocol |
|------------|----------|
| Client → ALB | TLS 1.3 |
| ALB → EC2 | HTTP (internal VPC) |
| EC2 → AWS Services | HTTPS (VPC endpoints) |

## 6.3 Authentication & Authorization

### Google OAuth

- Staff-only authentication
- ID token verification
- Session-based access control

### CSRF Protection

- 64-character random token per session
- Token validation on all POST requests

## 6.4 Network Security

| Control | Implementation |
|---------|----------------|
| No public IP on compute | EC2 in private subnet |
| No SSH access | SSM Session Manager only |
| Minimal security groups | Only required ports |
| VPC endpoints | AWS service access without internet |

## 6.5 Audit Logging

### Events Logged

| Event | Data Captured |
|-------|---------------|
| LOGIN | User ID, timestamp, IP address |
| LOGOUT | User ID, timestamp, IP address |
| MESSAGE_CREATE | User ID, message ID, subject |
| MESSAGE_DELETE | User ID, message ID |

### Log Retention

| Log Type | Retention |
|----------|-----------|
| Application logs | 90 days |
| Audit logs | 365 days |

---

# 7. Application Features

## 7.1 User Interface

### Landing Page
- Application branding
- Google OAuth login button
- Security messaging

### Dashboard
- Send new message (subject + body)
- View all messages
- Delete own messages
- User profile display

### Audit Log Viewer
- View all audit events
- Timestamp, user, action, details, IP

## 7.2 Core Functionality

| Feature | Description |
|---------|-------------|
| Authentication | Google OAuth login/logout |
| Send Message | Subject + body, stored in SQLite |
| View Messages | All users can see all messages |
| Delete Message | Users can delete their own messages |
| Audit Trail | All actions logged |

## 7.3 Security Features

| Feature | Implementation |
|---------|----------------|
| XSS Prevention | htmlspecialchars() on all output |
| SQL Injection Prevention | PDO prepared statements |
| CSRF Protection | Token-based validation |
| Session Security | HttpOnly, Secure cookies |
| Security Headers | CSP, X-Frame-Options, etc. |

---

# 8. Deployment Guide

## 8.1 Prerequisites

- AWS Account with BAA signed
- Domain name configured
- Google OAuth credentials
- Terraform installed (>= 1.0)
- AWS CLI configured

## 8.2 Local Development

```bash
# 1. Clone repository
git clone https://github.com/appcropolisdevops/awspoc.git
cd awspoc

# 2. Configure environment
cp .env.example .env
# Edit .env with Google OAuth credentials

# 3. Install dependencies
docker run --rm -v "$(pwd)/src:/app" -w /app composer:latest install

# 4. Start application
docker compose up -d

# 5. Access application
open http://localhost:8080
```

## 8.3 AWS Deployment

```bash
# 1. Navigate to Terraform directory
cd terraform

# 2. Create terraform.tfvars
cat > terraform.tfvars << EOF
aws_region           = "us-east-2"
project_name         = "hipaa-poc"
domain_name          = "taxplanner.app"
google_client_id     = "your-client-id"
google_client_secret = "your-client-secret"
EOF

# 3. Initialize Terraform
terraform init

# 4. Deploy infrastructure
terraform apply

# 5. Add DNS records (from Terraform output)
# - ACM validation CNAME
# - Domain CNAME to ALB

# 6. After DNS propagation, apply again
terraform apply
```

## 8.4 DNS Configuration

### Required Records

| Type | Name | Value |
|------|------|-------|
| CNAME | _acm-validation... | (from Terraform output) |
| CNAME | taxplanner.app | hipaa-poc-alb-xxx.us-east-2.elb.amazonaws.com |

## 8.5 Google OAuth Setup

1. Go to Google Cloud Console
2. Create OAuth 2.0 Client ID
3. Set authorized redirect URI: `https://taxplanner.app/login.php`
4. Copy credentials to terraform.tfvars

---

# 9. Operations Guide

## 9.1 Connecting to EC2

```bash
# Via AWS CLI
aws ssm start-session --target i-0500bfb3b4ad44e24 --region us-east-2
```

## 9.2 Application Management

```bash
# View containers
cd /app && docker compose ps

# View logs
docker compose logs -f php

# Restart application
docker compose restart

# Update application
git pull && docker compose up -d --build
```

## 9.3 Backup Operations

```bash
# Manual backup
/usr/local/bin/backup-db.sh

# List backups
aws s3 ls s3://hipaa-poc-backups-730543776652/backups/

# Restore from backup
aws s3 cp s3://bucket/backups/backup.sqlite.gz /tmp/
gunzip /tmp/backup.sqlite.gz
cp /tmp/backup.sqlite /data/db/app.sqlite
docker compose restart
```

## 9.4 Monitoring

### Health Check
```bash
curl -s -o /dev/null -w "%{http_code}" https://taxplanner.app/
```

### CloudWatch Logs
```bash
aws logs tail /hipaa-poc/application --follow --region us-east-2
```

## 9.5 Troubleshooting

| Issue | Solution |
|-------|----------|
| Application not loading | Check EC2 status, target group health |
| OAuth login failed | Verify redirect URI, check secrets |
| Database error | Check /data/db permissions |
| SSL error | Verify ACM certificate status |

---

# 10. Cost Breakdown

## 10.1 Monthly Estimated Costs

| Resource | Specification | Monthly Cost |
|----------|--------------|--------------|
| EC2 | t3.small (on-demand) | $15.18 |
| EBS | 30 GB gp3 | $2.40 |
| NAT Gateway | Per hour + data | $35.00 |
| ALB | Per hour + LCU | $20.00 |
| S3 | ~1 GB storage | $0.50 |
| Data Transfer | ~10 GB | $1.00 |
| CloudWatch | Logs | $2.00 |
| Secrets Manager | 1 secret | $0.40 |
| **Total** | | **~$76.48** |

## 10.2 Cost Optimization Options

| Option | Savings | Tradeoff |
|--------|---------|----------|
| Reserved Instance (1 yr) | ~40% on EC2 | Commitment |
| Spot Instance | ~70% on EC2 | Interruption risk |
| NAT Instance | ~$30/month | Management overhead |

---

# 11. Next Steps for Production

## 11.1 Recommended Improvements

| Area | Recommendation | Priority |
|------|----------------|----------|
| Database | Migrate to MongoDB Atlas | High |
| High Availability | Auto Scaling Group | High |
| CI/CD | GitHub Actions pipeline | Medium |
| Monitoring | CloudWatch Alarms, SNS alerts | Medium |
| WAF | AWS WAF on ALB | Medium |
| Backup | Cross-region replication | Low |

## 11.2 Multi-Tenant Architecture

For enterprise customers with dedicated containers:

1. ECS/EKS for container orchestration
2. Per-tenant database isolation
3. Tenant-specific S3 buckets
4. API Gateway for routing

## 11.3 Production Database

MongoDB Atlas (HIPAA-compliant):
- Dedicated cluster
- Encryption at rest
- Network peering with AWS VPC
- Automated backups

---

# 12. Appendix

## 12.1 Resource IDs

| Resource | ID |
|----------|-----|
| VPC | vpc-0dbc4f0061da966f5 |
| EC2 Instance | i-0500bfb3b4ad44e24 |
| ALB | hipaa-poc-alb |
| Target Group | hipaa-poc-tg |
| S3 (Backups) | hipaa-poc-backups-730543776652 |
| S3 (TF State) | hipaa-poc-tfstate-730543776652 |
| Secrets Manager | hipaa-poc/app-secrets |

## 12.2 Repository Structure

```
awspoc/
├── src/                    # Application source code
│   ├── public/             # Web root
│   ├── includes/           # PHP classes
│   ├── composer.json
│   └── composer.lock
├── docker/                 # Docker configuration
│   ├── nginx/
│   └── php/
├── terraform/              # Infrastructure as Code
│   ├── main.tf
│   ├── vpc.tf
│   ├── ec2.tf
│   ├── alb.tf
│   ├── s3.tf
│   ├── iam.tf
│   ├── security.tf
│   ├── secrets.tf
│   ├── outputs.tf
│   ├── variables.tf
│   └── user_data.sh
├── docs/                   # Documentation
├── docker-compose.yml
├── .env.example
└── README.md
```

## 12.3 HIPAA-Eligible Services Reference

All AWS services used in this POC are on the HIPAA-eligible services list:
https://aws.amazon.com/compliance/hipaa-eligible-services-reference/

## 12.4 Contact Information

| Role | Name | Contact |
|------|------|---------|
| DevOps Engineer | Naeem Dosh | Fiverr |
| Project Owner | Appcropolis | - |

---

**Document Version:** 1.0
**Last Updated:** January 31, 2026
**Classification:** Confidential
