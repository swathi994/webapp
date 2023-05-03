This is a web application that enables users to manage a single entity called User, which is stored in a database. The web application has a user interface in the form of a web page.
The webpage has three attributes:
• Username
• Date of birth
• Display name
The source code for the project and other necessary files are mantained in Github.
This web application is dockerized that includes the application and its dependencies.
The whole CI/CD process is automated using jenkins pipeline.

Instructions to run and maintain the application:

          1. Create Amazon Linux EC2 instance using terraform. (git clone the below code to launch an instance using terraform insetad of manual process using console)
          2. Login to EC2 instance using .pem file given at the time of creation.
                      ssh -i ".pem file name" ec2-user@ipaddress
          3. Install Java and Jenkins with ec2-user using the below commands.
          
                   sudo yum update –y  #software update
                   sudo wget -O /etc/yum.repos.d/jenkins.repo \ https://pkg.jenkins.io/redhat-stable/jenkins.repo #Add the Jenkins repo
                   sudo rpm --import https://pkg.jenkins.io/redhat-stable/jenkins.io-2023.key #Import a key file from Jenkins-CI to enable installation from the package
                   sudo yum upgrade
                   sudo dnf install java-11-amazon-corretto -y #Install java on Amazon Linux 2023
                   sudo amazon-linux-extras install java-openjdk11 -y #Install java on Amazon Linux 2
                   sudo yum install jenkins -y #Install Jenkins
                   sudo systemctl enable jenkins #Enable the Jenkins service to start at boot
                   sudo systemctl start jenkins #Start Jenkins as service

            
         
