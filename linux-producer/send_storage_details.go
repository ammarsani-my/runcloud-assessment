package main

import (
	"encoding/json"
	"fmt"
	"math"
	"time"

	amqp "github.com/rabbitmq/amqp091-go"
	"github.com/shirou/gopsutil/v4/disk"
)

const (
	RABBITMQ_HOST  = "localhost"
	RABBITMQ_PORT  = 5672
	RABBITMQ_USER  = "guest"
	RABBITMQ_PASS  = "guest"
	RABBITMQ_QUEUE = "storage_details"
)

type StorageDetails struct {
	TotalSpaceBytes     uint64  `json:"total_space_bytes"`
	FreeSpaceBytes      uint64  `json:"free_space_bytes"`
	FreeSpacePercentage float64 `json:"free_space_percentage"`
	UsedSpaceBytes      uint64  `json:"used_space_bytes"`
	UsedSpacePercentage float64 `json:"used_space_percentage"`
	StampedAt           string  `json:"stamped_at"`
}

func main() {
	conn, ch := establishRMQ()
	defer conn.Close()
	defer ch.Close()

	ticker := time.NewTicker(1 * time.Minute)
	defer ticker.Stop()

	publishRMQ(ch)

	for range ticker.C {
		publishRMQ(ch)
	}
}

func getDiskUsage() StorageDetails {
	usage, _ := disk.Usage("/")

	total := usage.Total
	free := usage.Free
	freePercent := calcPercent(free, total)
	used := usage.Used
	usedPercent := calcPercent(used, total)
	stampedAt := time.Now().Format("2006-01-02 15:04:05 PM")

	return StorageDetails{
		TotalSpaceBytes:     total,
		FreeSpaceBytes:      free,
		FreeSpacePercentage: freePercent,
		UsedSpaceBytes:      used,
		UsedSpacePercentage: usedPercent,
		StampedAt:           stampedAt,
	}
}

func establishRMQ() (*amqp.Connection, *amqp.Channel) {
	conn, _ := amqp.Dial(fmt.Sprintf("amqp://%s:%s@%s:%d/", RABBITMQ_USER, RABBITMQ_PASS, RABBITMQ_HOST, RABBITMQ_PORT))
	ch, _ := conn.Channel()

	ch.QueueDeclare(RABBITMQ_QUEUE, true, false, false, false, nil)

	return conn, ch
}

func publishRMQ(ch *amqp.Channel) {
	data := getDiskUsage()
	jsonData, _ := json.Marshal(data)

	ch.Publish("", RABBITMQ_QUEUE, false, false, amqp.Publishing{
		DeliveryMode: amqp.Persistent,
		ContentType:  "application/json",
		Body:         jsonData,
	})

	fmt.Printf(" [x] Sent Storage Details:\n")
	fmt.Printf("     Total Space: %s\n", formatBytes(data.TotalSpaceBytes))
	fmt.Printf("     Free Space:  %s (%.2f%%)\n", formatBytes(data.FreeSpaceBytes), data.FreeSpacePercentage)
	fmt.Printf("     Used Space:  %s (%.2f%%)\n", formatBytes(data.UsedSpaceBytes), data.UsedSpacePercentage)
	fmt.Printf("     Stamped At:  %s\n", data.StampedAt)
	fmt.Println()
}

func calcPercent(numerator uint64, denominator uint64) float64 {
	return math.Round(float64(numerator)/float64(denominator)*10000) / 100
}

func formatBytes(bytes uint64) string {
	if bytes == 0 {
		return "0 B"
	}

	units := []string{"B", "KB", "MB", "GB", "TB"}
	pow := math.Floor(math.Log(float64(bytes)) / math.Log(1024))

	if int(pow) >= len(units) {
		pow = float64(len(units) - 1)
	}

	value := float64(bytes) / math.Pow(1024, pow)

	return fmt.Sprintf("%.2f %s", value, units[int(pow)])
}
