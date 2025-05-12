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


/* Payload */

$total_space_bytes = disk_total_space('/');
$free_space_bytes = disk_free_space('/');
$free_space_percentage = round($free_space_bytes / $total_space_bytes * 100, 2);
$used_space_bytes = $total_space_bytes - $free_space_bytes;
$used_space_percentage = round($used_space_bytes / $total_space_bytes * 100, 2);
$stamped_at = date("Y-m-d H:i:s");

$data = json_encode(compact(
    'total_space_bytes',
    'free_space_bytes',
    'free_space_percentage',
    'used_space_bytes',
    'used_space_percentage',
    'stamped_at'
));


/* Produce message to Rabbit MQ queue */

$message = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($message, routing_key: RABBITMQ_QUEUE);

echo " [x] Sent Storage Details:\n";
echo "     Total Space: " . formatBytes($total_space_bytes) . "\n";
echo "     Free Space: " . formatBytes($free_space_bytes) . " ({$free_space_percentage}%)\n";
echo "     Used Space: " . formatBytes($used_space_bytes) . " ({$used_space_percentage}%)\n";
echo "     Timestamp: " . $stamped_at . "\n";
echo "\n";

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
