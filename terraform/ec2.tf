# Get latest Amazon Linux 2023 AMI
data "aws_ami" "amazon_linux" {
  most_recent = true
  owners      = ["amazon"]

  filter {
    name   = "name"
    values = ["al2023-ami-*-x86_64"]
  }

  filter {
    name   = "virtualization-type"
    values = ["hvm"]
  }
}

# EC2 Instance
resource "aws_instance" "app" {
  ami                    = data.aws_ami.amazon_linux.id
  instance_type          = var.instance_type
  subnet_id              = aws_subnet.private[0].id
  vpc_security_group_ids = [aws_security_group.ec2.id]
  iam_instance_profile   = aws_iam_instance_profile.ec2.name

  root_block_device {
    volume_size           = 20
    volume_type           = "gp3"
    encrypted             = true
    delete_on_termination = true

    tags = {
      Name = "${var.project_name}-root"
    }
  }

  # Data volume for database (encrypted)
  ebs_block_device {
    device_name           = "/dev/xvdf"
    volume_size           = 10
    volume_type           = "gp3"
    encrypted             = true
    delete_on_termination = false

    tags = {
      Name = "${var.project_name}-data"
    }
  }

  user_data = base64encode(templatefile("${path.module}/user_data.sh", {
    aws_region    = var.aws_region
    secret_arn    = aws_secretsmanager_secret.app_secrets.arn
    s3_bucket     = aws_s3_bucket.backups.id
    domain_name   = var.domain_name
  }))

  metadata_options {
    http_endpoint               = "enabled"
    http_tokens                 = "required"  # IMDSv2 required
    http_put_response_hop_limit = 1
  }

  tags = {
    Name = "${var.project_name}-app"
  }

  depends_on = [
    aws_nat_gateway.main,
    aws_secretsmanager_secret_version.app_secrets
  ]
}

# CloudWatch Log Group
resource "aws_cloudwatch_log_group" "app" {
  name              = "/hipaa-poc/application"
  retention_in_days = 90

  tags = {
    Name = "${var.project_name}-logs"
  }
}

resource "aws_cloudwatch_log_group" "audit" {
  name              = "/hipaa-poc/audit"
  retention_in_days = 365  # Keep audit logs for 1 year

  tags = {
    Name = "${var.project_name}-audit-logs"
  }
}
