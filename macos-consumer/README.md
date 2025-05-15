# PHP Application as Consumer
In this directory, there is a vanilla PHP application is created to consume the messages produce by producer. This app will treat the received messages and output it on console. The app is hosted within a host environment.

Dependency to communicate with Rabbit MQ: `php-amqplib`

To run the consumer application, execute the script by:

```bash
php receive_storage_details.php
```

By running this, the app will starts consuming any messages sent to queue named `storage_details`. The console will appear this at starts:

```bash
 [*] Waiting for messages. To exit press CTRL+C
```

Once any message produced, the console will output:

```bash
 [x] Received Storage Details::
     Total Space: xxx.xx GB
     Free Space: xx.xx GB (xx.xx%)
     Used Space: xx.xx GB (xx.xx%)
     Stamped At: 0000-00-00 00:00:00 AMPM
```