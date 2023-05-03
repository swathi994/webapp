This is a web application that enables users to manage a single entity called User, which is stored in a database. The web application has a user interface in the form of a web page.
The webpage has three attributes: Username, Date of birth and Display name.
The source code for the project and other necessary files are mantained in Github.
This web application is dockerized that includes the application and its dependencies.
The whole CI/CD process is automated using jenkins pipeline.

Instructions to run and maintain the application:

          1. Create Amazon Linux EC2 instance using terraform. (git clone the below code to launch an instance using terraform insetad of manual process using console)
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
          
           

 

                             
           


            
         
