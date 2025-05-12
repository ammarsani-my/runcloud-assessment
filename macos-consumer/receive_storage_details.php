<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/* Initiate connection to Rabbit MQ */

const RABBITMQ_HOST = 'localhost';
const RABBITMQ_PORT = 5672;
const RABBITMQ_USER = 'guest';
const RABBITMQ_PASS = 'guest';
const RABBITMQ_QUEUE = 'storage_details';

$connection = new AMQPStreamConnection(
    RABBITMQ_HOST,
    RABBITMQ_PORT,
    RABBITMQ_USER,
    RABBITMQ_PASS
);

$channel = $connection->channel();
$channel->queue_declare(RABBITMQ_QUEUE, durable: true, auto_delete: false);

/* Consume incoming message */

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function (AMQPMessage $message) {
    $data = json_decode($message->getBody(), true);

    echo " [x] Received Storage Details:\n";
    echo "     Total Space: " . formatBytes($data['total_space_bytes']) . "\n";
    echo "     Free Space: " . formatBytes($data['free_space_bytes']) . " ({$data['free_space_percentage']}%)\n";
    echo "     Used Space: " . formatBytes($data['used_space_bytes']) . " ({$data['used_space_percentage']}%)\n";
    echo "     Timestamp: " . $data['stamped_at'] . "\n";
    echo "\n";

    $message->ack();
};

$channel->basic_consume(RABBITMQ_QUEUE, callback: $callback);
$channel->consume();

/* Cleanup connection */                                

$channel->close();
$connection->close();

function formatBytes($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes, 1024) : 0));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}
