#Your task is to develop a web application that enables users to manage a
single entity called User, which is stored in a database. The web application
must have a user interface in the form of a web page.
The User entity must have three attributes:
• Username
• Date of birth
• Display name
To ensure the correctness of the application's functionalities, you need to
create tests.
To maintain the source code for the project and other necessary files, you
must use GitHub.
After developing the web application, you should create a containerized
deployment that includes the application and its dependencies using Docker.
To automate the testing, building, and deployment process, you need to
create a Jenkins pipeline that will first run the tests, then build a Docker image
of the application, and finally deploy the image to a Kubernetes cluster.
To manage the configuration of the application, including the database
connection information, Kubernetes should be used. This can be achieved by
storing the configuration in a configuration management tool.
Finally, you should document the implemented stack and the Jenkins
pipeline and provide instructions on how to run and maintain the application
