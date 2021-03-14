# AWS Notes

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

## References

- [Introduction to AWS Services](https://youtu.be/Z3SYDTMP3ME)
- [Create AWS account](https://youtu.be/KkWQuSwuGFc)
- [Set a billing alarm](https://youtu.be/2XilJFirnWY)
- [AWS Basics for Beginners - Full Course](https://youtu.be/ulprqHHWlng)
- [AWS calculator](https://calculator.aws/)
- [Downloading the AWS CLI](https://docs.aws.amazon.com/cli/latest/userguide/install-cliv2-windows.html)
