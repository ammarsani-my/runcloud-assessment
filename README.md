# runcloud-assessment
This repository contains an assessment conducted for RunCloud Senior Software Engineer

# The Task
Given a situation that you have Windows and run VirtualBox Virtual Machine (VM). The VM will be running Linux (Ubuntu, Centos, etc) and you run a PHP application inside the Windows to receive data from the Linux VM. Inside the Linux VM, you need to send the available disk space of the Linux VM to the PHP application. 

To obtain the available disk space, you need to write a piece of code using Go (or any compiled language of your choice) to send the available disk space to a message broker (using RabbitMQ, Apache Kafka or anything you find suitable). Inside the PHP application, you need to subscribe to the message broker to receive the data that was sent by the application that you have written inside the Linux. 

What you need to provide: 
1. A PHP application that is running inside the host which subscribed to a messaging broker. 
2. A config of a messaging broker. 
3. The source code of the application running inside the Linux VM to obtain the available disk space.  

Please put all the source code and configs inside your GitHub, GitLab or Bitbucket in THREE different folders 
1. PHP Application 
2. Message Broker 
3. Linux Application 

# The Solution
These are the considered to proceed with the assessment:
+ Host environment: Based on my availability, macOS is used as the host environment for the php application
+ Remote environment: A vm-alike which is docker is used to host the app for produce the necessary storage information hosted on Ubuntu Linux OS
+ Inter-app messaging: RabbitMQ is decided to explore more and becoming the mediator to pass the necessary message in between app

Within this documentation, the term/jargon used by RabbitMQ is used to describe each solution which are: producer, queue and consumer

## The Queue
Througout solving this assessment, RabbitMQ is hosted by running the latest version (`v4`) as a docker intance with the default configuration:

```shell
docker run -it --rm --name rabbitmq -p 5672:5672 -p 15672:15672 rabbitmq:4-management
```

Connection details:
- Host: `localhost`
- Port: `5672`
- Username: `guest`
- Password: `guest`

The queue for both to produce or consume is: `available_disk_space`

# Recap
To recap, the task given was good but challening in few aspect:
- Having experiences with multiple OS helped me to quickly imagined the solution.
- Used VM with few past project hence knowing that using docker could mock the linux environment to produce the necessary message to the consumer app
- Knowing a little knowledge but lack of experinece with implementing RabbitMQ or Kafka is a gap in this task. Decided to solve this via RabbitMQ, while figuring out, could say enabling a real-time experience thru web socket via Pusher in few projects previously has some similarity with using RabbitMQ.
