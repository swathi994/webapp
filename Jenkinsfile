pipeline {
  agent {
    label "docker-agent"
  }
  stages {
    stage ('Run Docker Compose') {
      steps{
        sh 'sudo yum install docker -y'
        sh 'sudo curl -L https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose'
        sh 'sudo chmod +x /usr/local/bin/docker-compose'
        sh 'sudo docker-compose up -d'
      }
    }
  }
}
