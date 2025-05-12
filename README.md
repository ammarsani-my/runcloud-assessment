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
These are the considered environment to proceed with the assessment:
+ Host environment: Based on my availability, macOS is used as the host environment for the application
+ Remote environment: A vm-alike which is docker is used to host the app for produce the necessary storage information hosted on Ubuntu Linux OS

The stack:
+ RabbitMQ is decided to be explored and becoming the mediator to pass the necessary message in between app
+ A cron scheduled PHP script that reading the available disk space and produce the message to the RabbitMQ queue
+ A PHP application that consume messages received from the RabbitMQ and displayed real-time on the page

Dependencies:
+ [php-amqplib](https://github.com/php-amqplib/php-amqplib) - both producer and consumer app utilised this library as a protocol to interact with RabbitMQ

> Within this documentation, the term / jargon used by RabbitMQ is used to standardise and easy understand of the solution provided which are: producer, queue and consumer

## The Queue
Througout solving this assessment, RabbitMQ is hosted by running the latest version (`v4`) as a docker instance with the default configuration:

```shell
docker run -it --rm --name rabbitmq -p 5672:5672 -p 15672:15672 rabbitmq:4-management
```

Connection details:
- Host: `localhost`
- Port: `5672`
- Username: `guest`
- Password: `guest`

The exhange of message in between producer and consumer is via: `available_disk_space`

## The Producer

## The Consumer

# Recap
To recap, the task given was good but challening in few aspect:
- Having experiences with multiple OS helped me to quickly imagined the solution.
- Used VM with few past project hence knowing that using docker could mock the linux environment to produce the necessary message to the consumer app
- Knowing a little knowledge but lack of experinece with implementing RabbitMQ or Kafka is a gap in this task. Decided to solve this via RabbitMQ, while figuring out, could say enabling a real-time experience thru web socket via Pusher in few projects previously has some similarity with using RabbitMQ.
