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
+ Remote environment: A vm-alike, docker is used to host the app for produce the necessary storage information hosted on Ubuntu Linux OS

The stack:
+ RabbitMQ is decided to be explored and becoming the mediator to pass the necessary message in between app
+ A cron scheduled PHP script that reading the available disk space and produce the message to the RabbitMQ queue
+ A PHP application that consume messages received from the RabbitMQ and displayed real-time on the console

Dependencies:
+ [php-amqplib](https://github.com/php-amqplib/php-amqplib) - both producer and consumer app utilised this library as a protocol to interact with RabbitMQ

> Within this documentation, the term / jargon used by RabbitMQ is used to standardise and easy understand of the solution provided which are: producer, queue and consumer

## The Queue
The details of this section is further described as in [/broker-rabbitmq/README.md](broker-rabbitmq/README.md)

## The Producer
The details of this section is further described as in [/linux-producer/README.md](linux-producer/README.md)

## The Consumer
The details of this section is further described as in [/macos-consumer/README.md](macos-consumer/README.md)

# Recap
To recap, the task given was good and challening. Here some of my notes while doing this assessment:
- Having experiences with multiple OS helped me to quickly imagined the possible solution.
- Working with VM and docker for past few projects acknowledge that docker could has almost similar linux setup except the major difference were on utilising GUI on VM compared to console on docker
- Knowing a little knowledge but lack of experinece with implementing RabbitMQ or Kafka is a gap in this task. Decided to solve this via RabbitMQ and being expose to it at the same time.
- While figuring out RabbitMQ, I realise that it enables a real-time message between few parties, consumer and producer. Based on experiences on using web socket via Pusher in few projects previously has some similarity with using RabbitMQ. However, the RabbitMQ as implemented in this solution is backend-backend communication. To display it on frontend needs more effort which might be easier thru Pusher in that case.
- From this assessment, many improvement could be considered such as:
    - further implementation thru integration with frontend
    - improved the configuration by using the exchange and binding for better scalability
    - limit message production only if there is changes in the storage details - this will optimize communication with the broker
