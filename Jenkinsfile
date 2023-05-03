pipeline {
  agent {
    label "docker-agent"
  }
  stages {
    stage ('Run Docker Compose') {
      steps{
        sh 'sudo yum install docker'
        sh 'sudo docker-compose up -d'
      }
    }
  }
}
