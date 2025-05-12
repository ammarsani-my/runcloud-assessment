# PHP Application as Producer
In this directory, there is a vanilla PHP application is created acting as producer producing messages containing storage details to be used later by the consumer. The app is hosted inside a remote environment (docker). This script will then be invoked by a scheduled cron job every minute:

```bash
* * * * * /usr/bin/php /app/send_storage_details.php
```

Every time the script is executed, a message will be produced. This will appear on console output:

```bash
 [x] Sent Storage Details:
     Total Space: xxx.xx GB
     Free Space: xx.xx GB (xx.xx%)
     Used Space: xx.xx GB (xx.xx%)
     Timestamp: Y-m-d H:i:s
```
