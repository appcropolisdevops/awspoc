========================================
Architecture Diagrams - PNG Format
========================================

Developer: Naeem Dosh (Fiverr)
Project: TaxPlanner.app
Date: February 3, 2026

GENERATED DIAGRAMS
==================

1. 01-high-level-architecture.png (62 KB)
   ----------------------------------------
   Overview of complete system architecture

   Shows:
   - Internet to Application Load Balancer
   - ALB with HTTPS/SSL
   - VPC with public/private subnets
   - EC2 instance with Docker containers
   - Nginx and PHP containers
   - SQLite database on encrypted EBS
   - S3 backups
   - Secrets Manager
   - Google OAuth integration
   - Security features summary

   Use for: Client presentations, documentation

2. 02-network-architecture.png (75 KB)
   ------------------------------------
   Detailed VPC and network design

   Shows:
   - VPC CIDR: 10.0.0.0/16
   - 2 Public subnets (with ALB, NAT)
   - 2 Private subnets (with EC2)
   - Internet Gateway
   - Routing tables
   - Security groups details
   - Network ACLs
   - Multi-AZ deployment

   Use for: Network planning, security reviews

3. 03-application-architecture.png (57 KB)
   ----------------------------------------
   Container-level application stack

   Shows:
   - EC2 instance details
   - Docker engine
   - Nginx container (port 80)
   - PHP container (port 9000)
   - Volume mounts
   - EBS data volume
   - Container features
   - Technology stack

   Use for: Development, troubleshooting

DIAGRAM FEATURES
================

Format: PNG (1400x900 to 1400x1100 pixels)
Quality: High resolution, suitable for:
  - Documentation
  - Presentations
  - Client meetings
  - Technical reviews
  - Printing

Colors:
  - Blue: Load balancer, public subnets
  - Green: Private subnets, Nginx
  - Orange: EC2 instance
  - Purple: VPC, PHP
  - Gray: Internet, supporting services

Text: Clear, readable fonts (DejaVu Sans)
Labels: All components clearly labeled
Details: IP ranges, ports, services

USAGE
=====

These diagrams can be:
1. Included in documentation PDFs
2. Shared with clients
3. Used in presentations
4. Embedded in markdown files
5. Printed for meetings

To embed in markdown:
  ![Architecture](diagrams/01-high-level-architecture.png)

To embed in HTML:
  <img src="diagrams/01-high-level-architecture.png" alt="Architecture">

TECHNICAL DETAILS
=================

All diagrams show the current production deployment:
- Application: https://taxplanner.app
- Region: us-east-2 (Ohio)
- VPC: vpc-0dbc4f0061da966f5
- Instance: i-04c7660dd799eda07
- Load Balancer: hipaa-poc-alb

Accurate as of: February 3, 2026

LOCATION
========

Diagrams: /home/dosh/Pictures/awspoc/docs/diagrams/
Total Size: ~194 KB (3 files)

Related Documentation:
- docs/ARCHITECTURE.md - Detailed text description
- docs/TERRAFORM-GUIDE.md - Infrastructure code
- docs/technical/ - Operations and security

NOTES
=====

These diagrams are generated programmatically using Python/PIL
and accurately reflect the deployed infrastructure.

For updates or modifications, regenerate using the Python script
or edit using image editing software.

========================================
Contact: Naeem Dosh (Fiverr)
Project: TaxPlanner.app HIPAA POC
========================================
