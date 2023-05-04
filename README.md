Application Details
=================================================

This is a web application that enables users to manage a single entity called User, which is stored in a database. The web application has a user interface in the form of a web page.
The webpage has three attributes: Username, Date of birth and Display name.
The source code for the project and other necessary files are mantained in Github.
This web application is dockerized that includes the application and its dependencies.
The whole CI/CD process is automated using jenkins pipeline.

Instructions to run and maintain application
=================================================

          1. Create Amazon Linux EC2 instance using terraform. (Launch an ec2 instance using terraform instead of creating it manually in aws concole. Install terraform on your local machine and git clone using "**https://github.com/swathi994/terraform.git**". Execute aws configure and provide your aws credentials. Then execute terraform init, terraform plan and terraform apply commands)
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
           10. Install Kubernetes in the different ubuntu instances using the procedure mentioned in the bottom of this readme.md file.
           10. Click on Build now in jenkins so that the pipeline gets executed. 
           11. Run http://ec2ipaddress so that webpage gets opened.
           
                Screenshot of webpage created: ![Screenshot from 2023-05-04 18-15-27](https://user-images.githubusercontent.com/33414899/236210470-82df8ec0-3772-48da-8ad8-f52638f67a25.png)
                   
           12. Fill username,dob and displayname & click on Submit. It will then redirects to http://ec2ipaddress/connect.php and displays as "new record inserted successfully" if it is successful insertion.
           
                Screenshot of successful details submission: ![Screenshot from 2023-05-04 18-26-19](https://user-images.githubusercontent.com/33414899/236211117-4068478e-10f3-4609-92c6-4236d83d204b.png)

                 
           13. Run ec2ipaddress:8083 so that myphpadmin page gets opened. Login it with the root user and password mentioned in docker-compose.yml. Check if the database named 'assignment' and table 'user' exists in mysql database.
                 
                 Screenshots of phpmyadmin page and records insertion from User interface: ![Screenshot from 2023-05-04 18-28-40](https://user-images.githubusercontent.com/33414899/236211741-15c2b2b9-f7d2-4b46-915a-e186aca8c145.png)  
                 ![Screenshot from 2023-05-04 18-29-42](https://user-images.githubusercontent.com/33414899/236211977-431e667f-513e-4a3f-b04c-b7544434af5f.png)
            
How the application works when pipeline executes
=================================================           

           1. Jenkins pipeline executes the jenkins file that contains cloning the git repo, execute tests, run docker-compose steps, push the docker images to docker hub and deploy the images to Kubernetes cluster.
           2. When docker-compose is run, it builds 3 containers for php apache, phpyadmin and mysqldb.
           
                Screenshot of docker containers: ![Screenshot from 2023-05-04 18-34-40](https://user-images.githubusercontent.com/33414899/236213118-5ac16b73-81f3-4806-b0e0-e5cccc738553.png)

           3. docker-compose.yml contains information of all the 3 containers mentioned above and it also calls the dockerfile inside it.
           4. mysqldb container consists of information about dbrootuserpassword, dbname and other needed parameters. .db folder that contains sql script for table creation is mounted on mysqldb container init volume.
           5. phpmyadmin container consists of information about pma host and port.
           6. dockerfile conists of php:apache image and other needed sql extensions.
           7. index.html and connect.php files are mounted on /var/www/html volume in php:apache container.
           8. index.html has all the html code which sends its post response to connect.php. Connect.php contains db information mentioned in docker-compose.yml & it creates db connection by inserting the records to respective user table successfully.
           9. Later the docker images are pushed to the docker hub.
           10.Then the docker images are deployed to Kubernetes cluster.
           
                  Screenshot of kubernetes nodes: ![Screenshot from 2023-05-04 18-45-54](https://user-images.githubusercontent.com/33414899/236215774-5c8654ec-2040-41e3-bb83-4a76289150e9.png)
                  
                  Screenshot of kubernetes pods: ![Screenshot from 2023-05-04 18-47-09](https://user-images.githubusercontent.com/33414899/236216064-683e3901-c529-41fb-939b-93be44669142.png)
          
Below are the steps to install Kuberntes cluster using kuebadm
=================================================

Building a Kubernetes 1.22 Cluster with kubeadm
=================================================
                    A. Install Packages
                    
                    1. Log into the Control Plane Node (Note: The following steps must be performed on all three nodes.).
                    2. Create configuration file for containerd:
                            cat <<EOF | sudo tee /etc/modules-load.d/containerd.conf
                            overlay
                            br_netfilter
                            EOF
                    3. Load modules:
                               sudo modprobe overlay
                               sudo modprobe br_netfilter
                    4. Set system configurations for Kubernetes networking:
                               cat <<EOF | sudo tee /etc/sysctl.d/99-kubernetes-cri.conf
                               net.bridge.bridge-nf-call-iptables = 1
                               net.ipv4.ip_forward = 1
                               net.bridge.bridge-nf-call-ip6tables = 1
                               EOF
                     5. Apply new settings:
                               sudo sysctl --system
                     6. Install containerd:
                               sudo apt-get update && sudo apt-get install -y containerd
                     7. Create default configuration file for containerd:
                               sudo mkdir -p /etc/containerd
                     8. Generate default containerd configuration and save to the newly created default file:
                               sudo containerd config default | sudo tee /etc/containerd/config.toml
                     9. Restart containerd to ensure new configuration file usage:
                               sudo systemctl restart containerd
                     10. Verify that containerd is running.
                               sudo systemctl status containerd
                     11. Disable swap:
                               sudo swapoff -a
                     12. Disable swap on startup in /etc/fstab:
                               sudo sed -i '/ swap / s/^\(.*\)$/#\1/g' /etc/fstab
                     13. Install dependency packages:
                               sudo apt-get update && sudo apt-get install -y apt-transport-https curl
                     14. Download and add GPG key:
                               curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add -
                     15. Add Kubernetes to repository list:
                               cat <<EOF | sudo tee /etc/apt/sources.list.d/kubernetes.list
                               deb https://apt.kubernetes.io/ kubernetes-xenial main
                               EOF
                     16. Update package listings:
                               sudo apt-get update
                     17. Install Kubernetes packages (Note: If you get a dpkg lock message, just wait a minute or two before
                         trying the command again):
                               sudo apt-get install -y kubelet=1.22.0-00 kubeadm=1.22.0-00 kubectl=1.22.0-00
                     18. Turn off automatic updates:
                               sudo apt-mark hold kubelet kubeadm kubectl
                     19. Log into both Worker Nodes to perform previous steps.

                     B. Initialize the Cluster
                     
                     1. Initialize the Kubernetes cluster on the control plane node using kubeadm (Note: This is only performed on the Control Plane Node):
                               sudo kubeadm init --pod-network-cidr 192.168.0.0/16 --kubernetes-version 1.22.0
                     2. Set kubectl access:
                                mkdir -p $HOME/.kube
                     3. sudo cp -i /etc/kubernetes/admin.conf $HOME/.kube/config
                            sudo chown $(id -u):$(id -g) $HOME/.kube/config
                     4. Test access to cluster:
                            kubectl get nodes
                     C. Install the Calico Network Add-on
                      
                     1. On the Control Plane Node, install Calico Networking:
                            kubectl apply -f https://docs.projectcalico.org/manifests/calico.yaml
                     2. Check status of the control plane node:
                            kubectl get nodes
                     D. Join the Worker Nodes to the Cluster
                       
                     1. In the Control Plane Node, create the token and copy the kubeadm join command (NOTE:The join command can also be found in the output from kubeadm init command):
                            kubeadm token create --print-join-command
                     2. In both Worker Nodes, paste the kubeadm join command to join the cluster. Use sudo to run it as root:
                            sudo kubeadm join ...
                     3. In the Control Plane Node, view cluster status (Note: You may have to wait a few moments to allow all nodes to become ready):
kubectl get nodes



