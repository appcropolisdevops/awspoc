variable "aws_region" {
  description = "AWS region"
  type        = string
  default     = "us-east-2"
}

variable "project_name" {
  description = "Project name for resource naming"
  type        = string
  default     = "hipaa-poc"
}

variable "domain_name" {
  description = "Domain name for the application"
  type        = string
  default     = "taxplanner.app"
}

variable "vpc_cidr" {
  description = "CIDR block for VPC"
  type        = string
  default     = "10.0.0.0/16"
}

variable "instance_type" {
  description = "EC2 instance type"
  type        = string
  default     = "t3.small"
}

variable "admin_ip" {
  description = "Admin IP for SSH access (optional, SSM preferred)"
  type        = string
  default     = ""
}

variable "google_client_id" {
  description = "Google OAuth Client ID"
  type        = string
  sensitive   = true
}

variable "google_client_secret" {
  description = "Google OAuth Client Secret"
  type        = string
  sensitive   = true
}

variable "app_secret" {
  description = "Application secret key"
  type        = string
  sensitive   = true
  default     = ""
}
