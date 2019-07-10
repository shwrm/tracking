terraform {
  required_version = "~> 0.11"

  backend "s3" {
    bucket = "terraform.shwrm.net"
    key    = "tracking.json"
    region = "eu-central-1"
  }
}

provider "aws" {
  version = "~> 2.11"
  region  = "eu-central-1"
}

provider "cloudflare" {
  version = "~> 1.14"
}

provider "random" {
  version = "~> 2.1"
}

locals {
  vpc-cidr             = "10.0.0.0/16"
  vpc-public-subnets   = ["10.0.11.0/24", "10.0.12.0/24"]

  name        = "tracking"
  environment = "production"
  count       = "1"

  users = [
    "konradk93",
    "Czarnolecki",
    "AcusticMr",
    "alaszewski",
    "jacekll",
    "k3dbe",
    "nuvolapl",
    "poziminski",
    "shwrmbot",
  ]

  admins = [
    "konradk93",
    "Czarnolecki",
    "alaszewski",
    "k3dbe",
    "michaljuda",
    "nuvolapl",
  ]
}

data "aws_availability_zones" "this" {}

module "vpc" {
  source  = "terraform-aws-modules/vpc/aws"
  version = "~> 1.64"

  name = "${local.name}"

  cidr             = "${local.vpc-cidr}"
  azs              = "${slice(data.aws_availability_zones.this.names, 0, length(local.vpc-public-subnets))}"
  public_subnets   = "${local.vpc-public-subnets}"

  enable_dns_hostnames = true

  enable_dhcp_options      = true
  dhcp_options_domain_name = "${local.name}"

  tags = {
    Name        = "${local.name}"
    Environment = "${local.environment}"
  }
}

resource "aws_key_pair" "tracking" {
  key_name_prefix = "tracking-"
  public_key      = "${file("~/.ssh/shwrm_aws_id_rsa.pub")}"
}
