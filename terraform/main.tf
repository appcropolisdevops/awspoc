terraform {
  required_version = ">= 1.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }

  backend "s3" {
    bucket         = "hipaa-poc-tfstate-730543776652"
    key            = "hipaa-poc/terraform.tfstate"
    region         = "us-east-2"
    encrypt        = true
    dynamodb_table = "hipaa-poc-tfstate-locks"
  }
}

provider "aws" {
  region = var.aws_region

  default_tags {
    tags = {
      Project     = "hipaa-poc"
      Environment = "poc"
      ManagedBy   = "terraform"
      Compliance  = "hipaa"
    }
  }
}

data "aws_availability_zones" "available" {
  state = "available"
}

data "aws_caller_identity" "current" {}
