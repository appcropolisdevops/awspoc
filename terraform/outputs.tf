output "alb_dns_name" {
  description = "DNS name of the Application Load Balancer"
  value       = aws_lb.main.dns_name
}

output "acm_certificate_arn" {
  description = "ARN of the ACM certificate"
  value       = aws_acm_certificate.main.arn
}

output "acm_validation_records" {
  description = "DNS records needed to validate the ACM certificate"
  value = {
    for dvo in aws_acm_certificate.main.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      type   = dvo.resource_record_type
      value  = dvo.resource_record_value
    }
  }
}

output "s3_bucket_name" {
  description = "Name of the S3 backup bucket"
  value       = aws_s3_bucket.backups.id
}

output "ec2_instance_id" {
  description = "ID of the EC2 instance"
  value       = aws_instance.app.id
}

output "vpc_id" {
  description = "ID of the VPC"
  value       = aws_vpc.main.id
}

output "private_subnet_ids" {
  description = "IDs of the private subnets"
  value       = aws_subnet.private[*].id
}

output "public_subnet_ids" {
  description = "IDs of the public subnets"
  value       = aws_subnet.public[*].id
}

output "ssm_connect_command" {
  description = "Command to connect to EC2 via SSM Session Manager"
  value       = "aws ssm start-session --target ${aws_instance.app.id} --region ${var.aws_region}"
}

output "secrets_manager_arn" {
  description = "ARN of the Secrets Manager secret"
  value       = aws_secretsmanager_secret.app_secrets.arn
}

output "next_steps" {
  description = "Next steps after deployment"
  value       = <<-EOT

    NEXT STEPS:
    ===========
    1. Add DNS CNAME record for ${var.domain_name}:
       - Name: ${var.domain_name}
       - Type: CNAME
       - Value: ${aws_lb.main.dns_name}

    2. Validate ACM certificate by adding DNS record:
       (See acm_validation_records output above)

    3. Update Google OAuth redirect URI to:
       https://${var.domain_name}/login.php

    4. Connect to EC2 via SSM:
       aws ssm start-session --target ${aws_instance.app.id} --region ${var.aws_region}

  EOT
}
