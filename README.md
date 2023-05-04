This is a web application that enables users to manage a single entity called User, which is stored in a database. The web application has a user interface in the form of a web page.
The webpage has three attributes: Username, Date of birth and Display name.
The source code for the project and other necessary files are mantained in Github.
This web application is dockerized that includes the application and its dependencies.
The whole CI/CD process is automated using jenkins pipeline.

Instructions to run and maintain the application:

          1. Create Amazon Linux EC2 instance using terraform. (Launch an ec2 instance using terraform instead of creating it manually in aws concole. Install terraform on your local machine and git clone using "https://github.com/swathi994/terraform.git". Then execute terraform init, terraform plan and terraform apply commands)
          2. Login to EC2 instance using .pem file given at the time of creation.
                      ssh -i ".pem file name" ec2-user@ipaddress
          3. Install Java and Jenkins using the below commands.
          
                   sudo yum update –y  #software update
                   sudo wget -O /etc/yum.repos.d/jenkins.repo \ https://pkg.jenkins.io/redhat-stable/jenkins.repo #Add the Jenkins repo
                   sudo rpm --import https://pkg.jenkins.io/redhat-stable/jenkins.io-2023.key #Import a key file from Jenkins-CI to enable installation from the package
                   sudo yum upgrade
                   sudo dnf install java-11-amazon-corretto -y #Install java on Amazon Linux 2023
                   sudo amazon-linux-extras install java-openjdk11 -y #Install java on Amazon Linux 2
                   sudo yum install jenkins -y #Install Jenkins
                   sudo systemctl enable jenkins #Enable the Jenkins service to start at boot
                   sudo systemctl start jenkins #Start Jenkins as service
                   sudo systemctl status jenkins #check the status of jenkins service
           4. Install Git using the below commands.
           
                   sudo yum install git #Install git
                   git version # to check git version
                   
           5. Access jenkins on ec2ipaddress:8080 on the browser (8080 port should be allowed as inbound rule on ec2 SG group)
           6. Configure jenkins by installing the needed plugins and creating username/continue as admin.
           7. Create a slave agent in jenkins with the name "docker-agent" and it should be Launched using the controller option.
                        Dashboard-> Manage Jenkins -> Manage nodes > create new node (provide the needed details)
           8. Bring the slave agent online by executing some commands on agent host terminal.
           9. Create a pipeline/multibranch pipeline project in jenkins by giving the below git repo and it's jenkinsfile path.
                         https://github.com/swathi994/webapp.git
           10. Click on Build now in jenkins so that the pipeline gets executed. 
           11. Run ec2ipaddress:8083 so that myphpadmin page gets opened. Login it with the root user and password mentioned in docker-compose.yml. Check if the database named 'assignment' and table 'user' exists in mysql database.
           12. Run http://ecipaddress so that webpage gets opened. Fill username,dob and displayname & click on Submit. It will then redirects to http://ec2ipaddress/connect.php and displays as "new record inserted successfully" if it is successful insertion.
           
           
How the application works when jenkins pipeline is executed:

           1. Jenkins pipeline executes the jenkins file that contains cloning the git repo, execute tests and run docker-compose steps.
           2. When docker-compose is run, it builds 3 containers for php apache, phpyadmin and mysqldb.
           3. docker-compose.yml contains information of all the 3 containers mentioned above and it also calls the dockerfile inside it.
           4. mysqldb container consists of information about dbrootuserpassword, dbname and other needed parameters. .db folder that contains sql script for table creation is mounted on mysqldb container init volume.
           5. phpmyadmin container consists of information about pma host and port.
           6. dockerfile conists of php:apache image and other needed sql extensions.
           7. index.html and connect.php files are mounted on /var/www/html volume in php:apache container.
           8. index.html has all the html code which sends its post response to connect.php. Connect.php contains db information mentioned in docker-compose.yml & it creates db connection by inserting the records to respective user table successfully.
           
Below are the steps if a docker image needs to be deployed on Kuberntes cluster:

           1. Push Docker Image to Docker Hub using Jenkins.

           2. Generate SSH Key on Kubernetes Client.

           3. Configure SSH Key in Jenkins.

           4. Create a Deployment file.

           5. Deploy Docker Image to Kubernetes Cluster Using Jenkinsfile

           6. Test the Deployment Pipeline       
           
How to install Kubernetes on AWS
           
          1. Setup Kubernetes (K8s) Cluster on AWS

          2. Create Ubuntu EC2 instance

          3. install AWSCLI

                 curl https://s3.amazonaws.com/aws-cli/awscli-bundle.zip -o awscli-bundle.zip
                 apt install unzip python
                 unzip awscli-bundle.zip
                 #sudo apt-get install unzip - if you dont have unzip in your system
                 ./awscli-bundle/install -i /usr/local/aws -b /usr/local/bin/aws

          4. Install kubectl on ubuntu instance

                  curl -LO https://storage.googleapis.com/kubernetes-release/release/$(curl -s https://storage.googleapis.com/kubernetes- release/release/stable.txt)/bin/linux/amd64/kubectl
                  chmod +x ./kubectl
                  sudo mv ./kubectl /usr/local/bin/kubectl
   
          5. Install kops on ubuntu instance


                   curl -LO https://github.com/kubernetes/kops/releases/download/$(curl -s https://api.github.com/repos/kubernetes/kops/releases/latest | grep tag_name | cut -d '"' -f 4)/kops-linux-amd64
                   chmod +x kops-linux-amd64
                   sudo mv kops-linux-amd64 /usr/local/bin/kops

          6. Create an IAM user/role with Route53, EC2, IAM and S3 full access


          7. Attach IAM role to ubuntu instance

          # Note: If you create IAM user with programmatic access then provide Access keys. Otherwise region information is enough
aws configure


          8. Create a Route53 private hosted zone (you can create Public hosted zone if you have a domain)

 
                   Routeh53 --> hosted zones --> created hosted zone  
                   Domain Name: user.net
                   Type: Private hosted zone for Amzon VPC

         9. create an S3 bucket

                     aws s3 mb s3://demo.k8s.user.net

         10. Expose environment variable:

                    export KOPS_STATE_STORE=s3://demo.k8s.user.net

         11. Create sshkeys before creating cluster

                         ssh-keygen

         12. Create kubernetes cluster definitions on S3 bucket
 
                      kops create cluster --cloud=aws --zones=ap-south-1b --name=demo.k8s.user.net --dns-zone=user.net --dns private

         13. Create kubernetes cluser

                     kops update cluster demo.k8s.user.net --yes

         14. Validate your cluster

                     kops validate cluster

         15. To list nodes

         16. kubectl get nodes
 

                             
           


            
         
