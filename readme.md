# AWS Notes

## Virtual Private Cloud (VPC)

- Multiple AZs per region
- Subnet in 1 AZ only
- Internet Gateway on VPC required for internet connectivity
  - Create a route table on the subnet, pointing at the IGW
- Private subnets don't have an IGW entry in the RT
  - Recommended to create RTs at the subnet level
  - By default, they will inherit the VPC RT
- Outbound internet traffic via a NAT located in a public subnet

### VPC-to-VPC Connectivity via VPC Peering

- Irrelevant of AWS account or region
- Non-transitive routing
  - Must be a direct connection
  - Cannot go indirect without a Transit Gateway
    - Acts as a hub
    - Connecting multiple VPCs

### VPC Endpoint (Gateway)

- Allows for S3 & DynamoDB traffic that would've been routed over the internet to connect directly
- Must be in the same region
  - Specified by route table entries

### VPC Endpoint (Interface)

- Allows for SNS, SES, CloudWatch, SQS, etc. traffic that would've been routed over the internet to connect directly
- Differs from VPC Endpoint by creating an Elastic Network Interface (ENI) in the subnet
  - Traffic routed through ENI

### Private Link

- SaaS provider exposes a single service in their VPC
  - Typically only the NLB exposed

## Security Groups & Network ACLs

- Security Groups only apply at the instance level but can been applied to VMs in any subnet - traffic going into and out of the NIC
  - Apply at the VPC level
  - Allow rules only - denies by default
  - Stateful
  - Evaluates all rules then works out what to do
  - Explicit association needed
  - By default, the security group allow all inbound traffic only from itself
  - *All* outbound traffic allowed
- Network ACLs apply to a subnet
  - Network traffic going between instances in the same subnet will be invisible to the network ACL; the network ACL only applies to traffic going into and out of the subnet
  - Applies the 1st rule it finds that qualifies
  - Stateless
  - Automatically applies to every instance in the subnet

### Firewalls

Stateful firewall allows return traffic by default - it doesn't need an allow rule; a stateless firewall needs an Allow rule

- Security Group - stateful
- Network ACL - stateless

## Public & Private Services

- Private services are created in a VPC
- Private IPs by default but can be made public; must be accessed over the internet

## AWS CLI

## Elastic Compute Cloud (EC2)

- Virtual server

### 3 types of IP address

- Public IP - lost when the instance is stopped; used in public subnets; not charged; associated with the private IP; cannot be moved between instances
- Private IP - retained when the instance is stopped; used in public and private subnets
- Elastic IP - static public IP address; charged if not used (Amazon incentivise use because of the short supply); associated with the private IP; can be moved between instances

So, if you have an instance with only a private IP, i.e. it's in a private subnet, external access must be via a NAT gateway in a public subnet, using route tables - private subnet route table points at NAT, NAT points at Internet Gateway

### AMI - Amazon Machine Image

- Instance type - indicates the power of the machine - t2.micro
- EBS - elastic block store - where the hard drive(s) are stored

## Access Keys

## Elastic Load Balancing

- Health probes
- Trigger to Auto Scaling to replace a bad instance

## Storage Services

### S3

- [Bucket naming rules](https://docs.aws.amazon.com/AmazonS3/latest/userguide/bucketnamingrules.html)
- Accessed over the internet
  - Unless using a VPC Endpoint (Gateway)

#### Object Lambda

- Tranforms data on access

### EFS

- Linux only

### EBS - Elastic Block Store

- Mount volumes - look like local drives
- Attached over the network
  - instance stores are physically attached to the host - high performance with low latency; ephereral - lost on instance shutdown - good for temp data; otherwise, copy it off

## RDS - Relational Database Service

- Runs on EC2 instances
  - Can choose/change the instance type
    - Vertical - with downtime
    - Scale out
      - multi-AZ
      - DR
      - primary is RDS master, passive standby in another AZ
        - Connection strings remapped automatically
    - Read replica
      - async replication
      - read-only
      - only queries are scaled, i.e. reads
      - You can't encrypt a r/o DB if the master is unencrypted
  - Managed service so no root access
- Types
  - Amazon Aurora
  - MySQL
  - MariaDB
  - Oracle
  - M/Soft SQL Server
  - PostgreSQL

## DynamoDB

- Key/value table
- Horiz., seamless scaling across partitions
- Replicated across multiple AZs

- Tables
  - Items = rows, attributes = columns

## CloudFormation

- Templates - IaC
- Visualiser

## Elastic Beanstalk

## Code Pipeline

### CodeCommit

- Code repo

### CodeBuild

### CodeDeploy

### CodeStar

## AWS Amplify

## Kinesis

## Route 53

- Domain registration
- DNS
- Routing policies
  - Simple
  - Failover
  - Geolocation
  - GeoProximity
  - Latency
- Health checks on EC2 instances

## CloudFront

- CDN
- Sourced from S3 or EC2
  - Origin
- Edge location cache static content
- Users directed to the nearest edge location

## Elastic Container Service (ECS)

- ECS Task is a running Docker container
  - Created from a Task definition
- ECS cluster is a logical grouping of tasks or ECS services
- Fargate is the managed, serverless platform
  - Alternatively, you can stand up EC2 instances, install some software, and run it from there

## AWS Lamda

- Runs for up to 15 mins
- Paid for by time & memory consumed

## Simple Notification Service

- Broadcasting

## Simple Queue Service

- Command messages
- Event source mapping
  - Mapping a Q service to a Lambda function

## Site-to-site VPN

- Connection between Virtual Private Gateway on the VPC & Customer Gateway on the on-premise DC
  - IPSEC protocol
  - 2 connections created by default for high availability
  - Traffic still flows over the internet

## Direct Connect

- Always-on private connections
  - High speed, low latency
  - Connection still into a Virtual Private Gateway

- Client VPN endpoint for point-to-site VPN

## References

- [Introduction to AWS Services](https://youtu.be/Z3SYDTMP3ME)
- [Create AWS account](https://youtu.be/KkWQuSwuGFc)
- [Set a billing alarm](https://youtu.be/2XilJFirnWY)
- [AWS Basics for Beginners - Full Course](https://youtu.be/ulprqHHWlng)
- [AWS calculator](https://calculator.aws/)
- [Downloading the AWS CLI](https://docs.aws.amazon.com/cli/latest/userguide/install-cliv2-windows.html)
- [AWS Docs](https://docs.aws.amazon.com/)
- [Setup a Site-to-site VPN](https://youtu.be/kqrWjR2Nn7Q)
  - [Step-by-step instructions](https://awstrainingcenter-test.s3-us-west-2.amazonaws.com/10+-+Setup+Site+to+Site+VPN+Connection+in+AWS.pdf)
- [Introduction to AWS Networking](https://youtu.be/XZbvQWkpJTI)
- [AWS Certified Cloud Practitioner Training 2020 - Full Course](https://youtu.be/3hLmDS179YE)
- ...
