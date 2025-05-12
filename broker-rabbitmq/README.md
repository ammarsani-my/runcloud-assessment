# Message Broker via Rabbit MQ
Througout solving this assessment, RabbitMQ is hosted by running the latest version (`v4`). The default configuration of the Rabbit MQ is running thru a docker instance. Hence, this files contains the configuration for the broker. The instance is intiated by this docker command:

```bash
docker run -it --rm --name rabbitmq -p 5672:5672 -p 15672:15672 rabbitmq:4-management
```

Connection details:
```
- Host: `localhost`
- Port: `5672`
- Username: `guest`
- Password: `guest`
```

The exhange of message in between producer and consumer is via queue named: `storage_details`
