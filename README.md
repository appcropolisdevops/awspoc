# TaxPlanner.app - Secure Messaging Application

[![HIPAA Compliant](https://img.shields.io/badge/HIPAA-Compliant-green.svg)](docs/HIPAA-POC-Documentation.html)
[![Status](https://img.shields.io/badge/status-live-brightgreen.svg)](https://taxplanner.app)
[![AWS](https://img.shields.io/badge/AWS-Deployed-orange.svg)](https://aws.amazon.com/)

A HIPAA-compliant secure messaging application with Google OAuth authentication, deployed on AWS.

**🌐 Live Application**: https://taxplanner.app

---

## 📚 Documentation

### For Users & Clients

👉 **Start here**: [Quick Start Guide](docs/QUICK-START.md) (5 minutes)

| Document | Description | Time |
|----------|-------------|------|
| **[Quick Start](docs/QUICK-START.md)** | Setup and start using in 5 minutes | 5 min |
| **[Client Guide](docs/CLIENT-GUIDE.md)** | Complete user guide with troubleshooting | 15 min |
| **[HIPAA Documentation](docs/HIPAA-POC-Documentation.html)** | Compliance and security information | 20 min |

### For Technical Staff

| Document | Description |
|----------|-------------|
| [Infrastructure Guide](docs/technical/INFRASTRUCTURE.md) | AWS architecture and resources |
| [Operations Manual](docs/technical/OPERATIONS.md) | Day-to-day operations and maintenance |
| [Security Guide](docs/technical/SECURITY.md) | Security controls and compliance |

---

## ⚡ Quick Start

### For First-Time Users

1. **Configure Google OAuth** (one-time, 5 minutes)
   - Go to: https://console.cloud.google.com/apis/credentials
   - Add redirect URI: `https://taxplanner.app/login.php`
   - See [Quick Start Guide](docs/QUICK-START.md) for details

2. **Access the Application**
   - Visit: https://taxplanner.app
   - Click "Sign in with Google"
   - Start messaging securely

### Need Help?

- **Setup Issues**: See [Quick Start Guide](docs/QUICK-START.md)
- **Using the App**: See [Client Guide](docs/CLIENT-GUIDE.md)
- **Troubleshooting**: See [Client Guide - Troubleshooting](docs/CLIENT-GUIDE.md#troubleshooting)

---

## 🏗️ Architecture Overview

```
Internet → ALB (HTTPS) → EC2 (Docker: PHP + Nginx) → SQLite (Encrypted)
                ↓
           S3 Backups (Encrypted)
```

**Key Components**:
- **Frontend**: Nginx web server
- **Backend**: PHP 8.2 with Google OAuth
- **Database**: SQLite on encrypted storage
- **Infrastructure**: AWS with HIPAA-eligible services
- **Security**: TLS 1.3, encrypted at rest, daily backups

---

## 🔒 Security Features

✅ **Authentication**: Google OAuth 2.0 (no passwords stored)
✅ **Encryption**: HTTPS/TLS 1.3 in transit, AES-256 at rest
✅ **Audit Logging**: All user actions logged
✅ **Network Security**: Private subnet, no direct internet access
✅ **Backup**: Daily encrypted backups to S3
✅ **Access Control**: IAM roles and security groups
✅ **HIPAA Compliant**: Using BAA-eligible AWS services

See [Security Guide](docs/technical/SECURITY.md) for details.

---

## 💡 Features

### User Features
- 🔐 **Secure Login** - Sign in with your Google account
- 💬 **Messaging** - Send and receive encrypted messages
- 🗑️ **Message Management** - Delete messages when needed
- 📱 **Mobile Friendly** - Works on all devices

### Security Features
- 🛡️ **CSRF Protection** - Token-based form security
- 🔒 **XSS Protection** - All user input sanitized
- 📝 **Audit Trail** - Complete activity logging
- 🔐 **Session Security** - Secure session management

### Administrative Features
- 📊 **CloudWatch Monitoring** - Application and infrastructure metrics
- 💾 **Automated Backups** - Daily backups to encrypted S3
- 🔧 **SSM Access** - Secure server access (no SSH keys)
- 📈 **Scalable** - Can scale as needed

---

## 🛠️ Technology Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Web Server** | Nginx (Alpine Linux) |
| **Application** | PHP 8.2-FPM |
| **Database** | SQLite (encrypted) |
| **Authentication** | Google OAuth 2.0 |
| **Containers** | Docker |
| **Cloud** | AWS (EC2, ALB, S3, etc.) |
| **Infrastructure** | Terraform (IaC) |

---

## 📊 System Status

**Application**: https://taxplanner.app
- **Status**: ✅ Live and operational
- **Instance**: i-04c7660dd799eda07 (EC2)
- **Region**: us-east-2 (Ohio)
- **SSL**: Valid (ACM certificate)
- **Health**: All targets healthy

### Quick Health Check

```bash
# Check if application is accessible
curl -I https://taxplanner.app

# Expected: HTTP/2 200
```

---

## 📁 Project Structure

```
awspoc/
├── docs/                        # 📚 Documentation
│   ├── QUICK-START.md          # Start here!
│   ├── CLIENT-GUIDE.md         # User guide
│   ├── HIPAA-POC-Documentation.html
│   └── technical/              # Technical docs
│       ├── INFRASTRUCTURE.md
│       ├── OPERATIONS.md
│       └── SECURITY.md
│
├── src/                        # 💻 Application code
│   ├── public/                 # Web accessible files
│   │   ├── index.php
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   └── assets/
│   └── includes/               # PHP classes
│       ├── Auth.php
│       ├── Database.php
│       └── Message.php
│
├── terraform/                  # 🏗️ Infrastructure
│   ├── main.tf
│   ├── vpc.tf
│   ├── alb.tf
│   ├── ec2.tf
│   └── ...
│
├── docker/                     # 🐳 Container configs
│   ├── nginx/
│   └── php/
│
└── README.md                   # 👈 You are here
```

---

## 🚀 Deployment Information

**Current Deployment**:
- **URL**: https://taxplanner.app
- **Environment**: Production
- **Region**: AWS us-east-2 (Ohio)
- **Deployed**: February 3, 2026
- **Version**: 1.0

**Infrastructure**:
- **Load Balancer**: hipaa-poc-alb-235408071.us-east-2.elb.amazonaws.com
- **Instance Type**: t3.small
- **Database**: SQLite (28KB, encrypted EBS)
- **Backups**: Daily at 2:00 AM UTC → S3

---

## 💰 Cost Estimate

| Resource | Monthly Cost |
|----------|-------------|
| EC2 t3.small | ~$15 |
| ALB | ~$16-20 |
| EBS Storage | ~$4 |
| S3 Backups | ~$1 |
| Data Transfer | ~$2 |
| Other Services | ~$2 |
| **Total** | **~$40-45/month** |

*Note: Costs may vary based on usage and data transfer*

---

## 🔧 Administration

### Common Tasks

**View Application Logs**:
```bash
aws logs tail /hipaa-poc/application --follow --region us-east-2
```

**Connect to Server**:
```bash
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2
```

**Check Container Status**:
```bash
# After connecting via SSM
docker ps
docker logs app-nginx
docker logs app-php
```

**Manual Backup**:
```bash
# After connecting via SSM
sudo /usr/local/bin/backup-db.sh
```

See [Operations Manual](docs/technical/OPERATIONS.md) for detailed procedures.

---

## 🆘 Support & Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| **Can't log in** | Check [Quick Start Guide](docs/QUICK-START.md) - OAuth setup |
| **"Redirect URI mismatch"** | Verify Google OAuth redirect URI |
| **Page won't load** | Check [Troubleshooting Guide](docs/CLIENT-GUIDE.md#troubleshooting) |
| **Database error** | Permissions issue - see [Operations Manual](docs/technical/OPERATIONS.md) |

### Getting Help

1. **Check Documentation**
   - [Quick Start Guide](docs/QUICK-START.md)
   - [Client Guide](docs/CLIENT-GUIDE.md)
   - [Troubleshooting Section](docs/CLIENT-GUIDE.md#troubleshooting)

2. **Contact Support**
   - Provide error message
   - Include screenshot
   - Describe steps taken

3. **Emergency**
   - For security incidents, contact immediately
   - Follow your organization's incident response procedures

---

## 📋 Quick Reference

### Important URLs

| Purpose | URL |
|---------|-----|
| **Application** | https://taxplanner.app |
| **Google Console** | https://console.cloud.google.com/apis/credentials |
| **AWS Console** | https://console.aws.amazon.com/ |

### OAuth Configuration

**Redirect URI**: `https://taxplanner.app/login.php`

This must be added to your Google OAuth client's authorized redirect URIs.

---

## 📝 Version History

**Version 1.0** (February 3, 2026)
- ✅ Initial production deployment
- ✅ HTTPS with valid SSL certificate
- ✅ Database permissions configured
- ✅ All health checks passing
- ✅ Backup system operational

---

## 📄 License & Compliance

**License**: Proprietary - All rights reserved

**HIPAA Compliance**:
- Application designed for HIPAA compliance
- Uses BAA-eligible AWS services
- Implements required technical safeguards
- See [HIPAA Documentation](docs/HIPAA-POC-Documentation.html) for details

**Business Associate Agreement**:
- Ensure BAA is signed with AWS
- Review security controls periodically
- Maintain audit logs as required

---

## 🔗 Additional Resources

- **AWS HIPAA Compliance**: https://aws.amazon.com/compliance/hipaa-compliance/
- **Google OAuth Documentation**: https://developers.google.com/identity/protocols/oauth2
- **PHP Security Best Practices**: https://www.php.net/manual/en/security.php

---

## 📞 Contact

**Application URL**: https://taxplanner.app

**Technical Support**: Contact your system administrator

**Documentation**: See `docs/` folder for comprehensive guides

---

**Last Updated**: February 3, 2026
**Status**: ✅ Operational
**Maintained By**: DevOps Team
