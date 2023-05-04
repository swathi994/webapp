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
                   sh 'docker tag webapp-www swathi267/webapp-www'
                   sh 'docker push swathi267/webapp-www'
                   sh 'docker tag mysql swathi267/mysql'
                   sh 'docker push swathi267/mysql'
                   sh 'docker tag phpmyadmin swathi267/phpmyadmin'
                   sh 'docker push swathi267/phpmyadmin'
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
