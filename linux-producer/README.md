# Go Application as Producer
In this directory, there is a Go application created acting as producer producing messages containing storage details to be used later by the consumer. The app is hosted inside a remote environment (linux in docker).

Possible Dependencies to Retrieve Storage Details:
- via `syscall` - legacy
- via `golang.org/x/sys/unix` - newer, better, Go-idiomatic
- via `github.com/shirou/gopsutil/v4/disk` - os agnostic (used in this solution)

Dependency to communicate with Rabbit MQ: `rabbitmq/amqp091-go`

To start stamping data, run:

```bash
go run .
```

Running the code starts retrieving disk details every minute.

Every time the script is executed, a message will be produced. This will appear on console output:

```bash
 [x] Sent Storage Details:
     Total Space: xxx.xx GB
     Free Space: xx.xx GB (xx.xx%)
     Used Space: xx.xx GB (xx.xx%)
     Stamped At: 0000-00-00 00:00:00 AMPM
```