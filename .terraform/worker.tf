resource "aws_security_group" "tracking" {

  vpc_id = "${module.vpc.vpc_id}"

  ingress {
    from_port = 22
    to_port   = 22
    protocol  = "TCP"

    cidr_blocks = [
      "0.0.0.0/0",
    ]
  }

  ingress {
    from_port = 8080
    to_port   = 8080
    protocol  = "TCP"

    cidr_blocks = [
      "0.0.0.0/0",
    ]
  }

  egress {
    from_port = 0
    to_port   = 0
    protocol  = -1

    cidr_blocks = [
      "0.0.0.0/0",
    ]
  }

  lifecycle {
    create_before_destroy = true
  }

  tags = {
    Name        = "${local.name}"
    Environment = "${local.environment}"
  }
}

data "aws_ami" "tracking" {
  most_recent = true

  filter {
    name   = "name"
    values = ["Tracking *"]
  }

  owners = ["self"]
}

resource "aws_instance" "tracking" {
  ami           = "${data.aws_ami.tracking.id}"
  instance_type = "t3.micro"
  monitoring    = true
  key_name      = "${aws_key_pair.tracking.key_name}"
  count         = 1

  vpc_security_group_ids = [
    "${aws_security_group.tracking.id}",
  ]

  subnet_id = "${element(module.vpc.public_subnets, count.index)}"

  provisioner "remote-exec" {
    inline = [
      "${(formatlist("curl -sS -o- https://github.com/%s.keys | tee -a ~/.ssh/authorized_keys", local.admins))}",
      "${(formatlist("curl -sS -o- https://github.com/%s.keys | sudo -u tracking tee -a /home/tracking/.ssh/authorized_keys", local.users))}",
      "ssh-keyscan -H github.com | sudo -u tracking tee -a /home/tracking/.ssh/known_hosts",
    ]

    connection {
      type        = "ssh"
      user        = "admin"
      private_key = "${file("~/.ssh/shwrm_aws_id_rsa")}"
    }
  }

    connection {
      type        = "ssh"
      user        = "admin"
      private_key = "${file("~/.ssh/shwrm_aws_id_rsa")}"
    }

    lifecycle {
      create_before_destroy = true
    }

  tags = {
    Name        = "${local.name}"
    Environment = "${local.environment}"
    CountIndex  = "${count.index}"
    Service     = "tracking"
  }
}

resource "aws_eip" "tracking" {
  count    = "${aws_instance.tracking.count}"
  instance = "${element(aws_instance.tracking.*.id, count.index)}"
  vpc      = true

  tags = {
    Name        = "${local.name}"
    Environment = "${local.environment}"
    CountIndex  = "${count.index}"
  }
}

resource "aws_lb_target_group" "tracking" {
  port     = 8080
  protocol = "HTTP"
  vpc_id   = "${module.vpc.vpc_id}"

  health_check = {
    path = "/status"
  }
}

resource "aws_lb_target_group_attachment" "tracking" {
  count            = "${aws_instance.tracking.count}"
  target_group_arn = "${aws_lb_target_group.tracking.arn}"
  target_id        = "${element(aws_instance.tracking.*.id, count.index)}"
}

resource "aws_lb_listener" "tracking" {
  load_balancer_arn = "${aws_lb.tracking.arn}"
  port              = 443
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-TLS-1-2-2017-01"
  certificate_arn   = "${data.aws_acm_certificate.tracking.arn}"

  default_action {
    target_group_arn = "${aws_lb_target_group.tracking.arn}"
    type             = "forward"
  }
}

data "aws_acm_certificate" "tracking" {
  domain = "*.shwrm.net"
}

module "lb_sg" {
  version = "~> 2.1.0"
  source = "terraform-aws-modules/security-group/aws"
  name   = "load-balancer"
  vpc_id = "${module.vpc.vpc_id}"

  ingress_with_cidr_blocks = [
    {
      rule        = "https-443-tcp"
      cidr_blocks = "0.0.0.0/0"
    },
  ]

  egress_rules = ["all-all"]
}

resource "aws_s3_bucket" "access_logs" {
  bucket_prefix = "tracking-"
}

resource "aws_s3_bucket_policy" "access_logs" {
  bucket = "${aws_s3_bucket.access_logs.id}"
  policy = "${data.aws_iam_policy_document.bucket_policy.json}"
}

data "aws_caller_identity" "current" {}

data "aws_elb_service_account" "main" {}

data "aws_iam_policy_document" "bucket_policy" {
  statement {
    sid = "AllowToPutLoadBalancerLogsToS3Bucket"

    actions = [
      "s3:PutObject",
    ]

    resources = [
      "arn:aws:s3:::${aws_s3_bucket.access_logs.bucket}/AWSLogs/${data.aws_caller_identity.current.account_id}/*",
    ]

    principals {
      type        = "AWS"
      identifiers = ["arn:aws:iam::${data.aws_elb_service_account.main.id}:root"]
    }
  }
}

resource "aws_lb" "tracking" {
  name                       = "tracking"
  security_groups            = ["${module.lb_sg.this_security_group_id}"]
  subnets                    = ["${module.vpc.public_subnets}"]
  enable_deletion_protection = true

  access_logs {
    bucket  = "${aws_s3_bucket.access_logs.bucket}"
    enabled = true
  }
}

output "LB" {
  value = "${aws_lb.tracking.dns_name}"
}

resource "cloudflare_record" "tracking" {
  domain = "shwrm.net"
  name   = "tracking"
  value  = "${aws_lb.tracking.dns_name}"
  type   = "CNAME"
}
