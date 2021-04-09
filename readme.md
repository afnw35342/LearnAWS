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

### Security Groups & Network ACLs

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

## Computing

### Elastic Compute Cloud (EC2)

- Virtual server
- Limits dashboard???

#### 3 types of IP address

- Public IP - lost when the instance is stopped; used in public subnets; not charged; associated with the private IP; cannot be moved between instances
- Private IP - retained when the instance is stopped; used in public and private subnets
- Elastic IP - static public IP address; charged if not used (Amazon incentivise use because of the short supply); associated with the private IP; can be moved between instances

So, if you have an instance with only a private IP, i.e. it's in a private subnet, external access must be via a NAT gateway in a public subnet, using route tables - private subnet route table points at NAT, NAT points at Internet Gateway

#### AMI - Amazon Machine Image

- Instance type - indicates the power of the machine - t2.micro
- EBS - elastic block store - where the hard drive(s) are stored

#### Access Keys

#### Inspector

- Agent to assess selected EC2 instance against a Security Benchmark
  - Variety of benchmarks to compare against
- Outputs a PDF

### Elastic Beanstalk

- Services for deploying & scaling web apps
  - EC2, S3, SNS, CloudWatch, autoscaling, ELBs
- Upload packaged code
- Intended for developer environments

### Elastic Container Service (ECS)

- ECS Task is a running Docker container
  - Created from a Task definition
- ECS cluster is a logical grouping of tasks or ECS services
- Platform is EC2

### Fargate

- Managed, serverless platform
  - Alternatively, you can stand up EC2 instances, install some software, and run it from there
- Similar to Lambda - pay for CPU time

### AWS Lamda

- Serverless functions
- Runs for up to 15 mins
- Paid for by time & memory consumed

### EKS

- Managed K8s as a service

### AWS Batch

- Scheduling EC2 spot instances

## Elastic Load Balancing (ELB)

- Health probes
- Trigger to Auto Scaling to replace a bad instance

## Storage Services

### S3

- [Bucket naming rules](https://docs.aws.amazon.com/AmazonS3/latest/userguide/bucketnamingrules.html)
- Accessed over the internet
  - Unless using a VPC Endpoint (Gateway)
- S3 object lambda modifies the object on GET

#### Object Lambda

- Tranforms object data on GET access
  - E.g. Change data format, change video encoding

#### Glacier

- Intended for archiving & long-term backup
- Low cost...
  - ...but the trade-off is...
- Slow retrieval time
  - Measured in minute and probably hours
  - Also, significant retrieval cost
- Use case is data retention for regulatory purposes
  - Unlikely to need to retrieve it but just in case...

### Storage Gateway

- Extension of on-prem storage into the cloud
- Backup & archiving, DR, data processing in the cloud, stroage tiering, data migration

### EFS

- Linux only
- One Zone - aging data management
- Shared file store, mountable concurrently on multiple EC2 instances

### EBS - Elastic Block Store

- Virtual hard-drive in the cloud
  - SSD
  - IOPS SSD
  - Throughput HDD
  - Cold HHD
- Mount volumes - look like local drives
- Attached over the network
  - instance stores are physically attached to the host - high performance with low latency; ephereral - lost on instance shutdown - good for temp data; otherwise, copy it off

### Snowball

- Data movement but not over the network
- Loading data in bulk - 50-80TB
- E-ink label
- Tamperproof
- 3 Levels of cryptography
- on-board compute
- Ruggedised

#### Edge

- Better version of Snowball

### Snowmobile

- larger version of Snowball
- Shipping container

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
  - snapshots restore to a new instace, not to the original source
- Types
  - Amazon Aurora
    - Serverless option
    - Fully managed
  - MySQL
  - MariaDB
  - Oracle
  - M/Soft SQL Server
  - PostgreSQL

### DynamoDB

- Key/value table
- Horiz., seamless scaling across partitions
- Replicated across multiple AZs
- Has its roots in Cassadra

- Tables
  - Items = rows, attributes = columns

### DocumentDB

- MongoDB-compatible

### Neptune

- Managed graph DB

### Redshift

- Columnar DB
- Sharing data between instances
- No data movement
- Define shared objects, and consumers
- Define consumers access to newly shared objects

### ElastiCache

- Redis
- MemCacheD

## CloudFormation

- Templates - IaC
  - JSON or YAML
- Visualiser
- Recommendation is to always modify through a new CloudFormation deployment
- Update the schema file to update the deployment back to a consistent state, then update it again to resolve the resource issue
- Complex option but very powerful

## Code Pipeline

### CodeCommit

- Code repo

### CodeBuild

### CodeDeploy

### CodeStar

## AWS Amplify

## Kinesis

- Streaming

## Ground Truth?

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

### Web Application Firewall (WAF)

- Attached to CloudFront or Application Load Balancer
- Rulesets available from the Marketplace

## Simple Notification Service

- Broadcasting

## Simple Queue Service

- Command messages
- Event source mapping
  - Mapping a Q service to a Lambda function

## Networking

### Site-to-site VPN

- Connection between Virtual Private Gateway on the VPC & Customer Gateway on the on-premise DC
  - IPSEC protocol
  - 2 connections created by default for high availability
  - Traffic still flows over the internet

### Direct Connect

- Always-on private connections
  - High speed, low latency - gigabit
  - Connection still into a Virtual Private Gateway
- Fiber optics

#### VPN Connect

- Belt-n-braces security for Direct Connect

### Client VPN

- Endpoint for point-to-site VPN

## DeepComposer

- ML learning based
- physical keyboard
- music composing
- Custom models from sage maker

## Cloud Shell

- Linux shell

## Lookout for Metrics

- ML looking for anomolies in data

## Amazon Certificate Manager

- Manages renewal notifications

## IAM Access Analyser

- Policy validation
- Warnings about over-permissive access policies

## Comprehend

- Understanding text through ML analysis
- Can ID PII

## Amazon Managed Service for Grafana (AMG)

## Trusted Advisor

- Assess the deployed resources and provides guidance
  - Unused IP addresses
  - Idle load balancers
  - Holistic view across multiple services

## TCO Calculator

- ...

## Landing Zones

- Enterprises setup for multi-account landscape
- Account Vending Machine (AVM)
  - Service catalog templates
- SSO
- Customisable via LZ configs
- 4-account baseline - recommended baseline to have purposeful, isolated accounts
  - Master
  - Shared Services
  - Log Archive
  - Security account
- Add additional accounts via the AVM

## Resource Groups & Tagging

- Tags - metadata for organising your AWS resources
- Resource Groups - resources that share 1 or more tags
  - RGs can be tag-based or CloudFormation-based

## AWS QuickStarts

- Pre-built templates
- Filterable & searchable
- CloudFormation template

## OpsWorks

- Config management service
- Managed instances of Chef and/or Puppet (receipies in Ruby)

## Marketplace

- Software listings from '000s of ISVs

## Amazon Connect

- Call centre service
  - Automated phone system
- Inbound & outbound calls
- Workflow
- Recording into [S3](#s3) for analysis via Comprehend

## WorkSpaces

- VDI

## WorkDocs

- AWS' version of SharePoint

## Chime

- Online meetings, video conferencing
- Scales to meet demand

## WorkMail

- Managed business e-mail
- GMail on AWS

## PinPoint

- Marketing segmentation
- E-mail, SMS, Push notifications

## Simple E-mail Service (SES)

- Cloud-based e-mail service
- Supports HTML, unlike SNS
- Can receive incoming e-mails
- E-mail templates
- Custom domain name
- Monitor your e-mail reputation

## Simple Notification Service (SNS)

- Text only
- Outbound notifications only
- Pub/Sub
  - Topics are subscribed to
- Supports multiple protocols
  - HTTP, E-mail, SQS, SMS

## Simple Queue Service (SQS)

- 14 day retention
- Sequential or parallel
- Guaranteed at least 1-time delivery

## QuickSight

- BI data visualiser
- Connects multiple data sources
- No/low code
- ML component
- Shared via dashboards

## AWS Managed Microsoft AD

- AWS directory Service for M/soft Active Directory
- Enables directory-aware apps to use manaded A/D on AWS

## Logging

### CloudTrail

- Logs all API calls
- Who is to blame?
- Developer mis-config
- Malicious actors
- Automate responses
  - Tagging

### CloudWatch

- Collection of services
  - Primarily, logs
    - Perf. data about AWS services, e.g. CPU utilisation, memory usage, etc.
    - Application logs
    - Lambda
  - Everything else is derived from Logs
  - Metrics - time-series data points usually graphed on a dashboard
  - Events - do something in response to something
  - Alarms - notifications based on a threshold breach
  - Dashboard - visualisation of metrics

#### Logs

- Agent installed on EC2

## AWS Shield

- DDoS protection
- Sits behind Route 53 and/or CloudFront
- Turned on by default when traffic is routed whrough 1 of these services
- Protection at Layers 3 (network), 4 (transport), & 7 (application)
- Free tier
- Paid tier - $3k/year
  - Larger, more sophisticated attacks

## Guard Duty

- Threat Detection Service
  - Intrusion Detection System (IDS) / Intrusion Protection System (IPS)
- Monitors for malicious activity or policy violations
  - Uses ML to analyse logs from...
    - CloudTrail
    - VPC Flow
    - DNS
  - Responses can be automated via CloudWatch events

## Key Management Service (KMS)

- Multi-tenant HSM
  - Hardware security module
- Simple checkbox on many services
- Envelope encryption
  - Key encrypted, placed in a digital envelope; envelope encrypted with a master key

## Macie

- Uses ML to continuously analyse S3 data access activity for anomolies
  - CloudTrail logs
  - Generates alerts
    - Large variety of alerts

## CloudSearch

- Search engine that adds a search bar

## Media Connect

- New version of Elastic Transcoder
- Video transcoder
  - Watermark
  - Different video formats
  - Automated editing - intro & outro added automatically

### Benefits of using Elemental MediaConvert

- Overlays images
- Insert video clips
- Extract captions data
- Robust UI

## Artifact

- Compliance report based on selected regulatory framework
- Generates a PDF that contains an Excel spreadie with more info

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
- [AWS VPC Basics - Understanding what is VPC and Calculating CIDR for VPC and Subnets](https://youtu.be/O3fgul-fJCk)
- [AWS VPC Public and Private Subnets](https://youtu.be/4T9G9nv0GIk)
- ...
