pipeline {
  agent {
    label "docker-agent"
  }
  stages {
    stage('Cloning Git') {
      steps {
        git([url: 'https://github.com/swathi994/webapp.git', branch: 'master', credentialsId: 'jenkins-user'])
       }
    }
    stage ('Execute tests') {
      steps{
       sh ' sudo systemctl status docker'
      } 
    }
    stage ('Run Docker Compose') {
      steps{
        sh 'sudo yum install docker -y'
        sh 'sudo curl -L https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose'
        sh 'sudo chmod +x /usr/local/bin/docker-compose'
        sh 'sudo systemctl start docker'
        sh 'sudo docker-compose up -d'
      }
    }
    stage('Push image to Hub'){
            steps{
                script{
                   withCredentials([string(credentialsId: 'dockerhub-pwd', variable: 'dockerhubpwd')]) {
                   sh 'docker login -u swathi267 -p ${dockerhubpwd}'

}
                   sh 'docker push php:apache'
                   sh 'docker push mysql:latest'
                   sh 'docker push phpmyadmin/phpmyadmin'
                }
            }
        }
     stage('Deploy to k8s'){
            steps{
                script{
                    kubernetesDeploy (configs: 'deploymentservice.yaml',kubeconfigId: 'k8sconfigpwd')
                    kubernetesDeploy (configs: 'service.yaml',kubeconfigId: 'k8sconfigpwd')
                }
            }
        }
  }
}
