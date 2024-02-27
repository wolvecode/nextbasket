<?php

require(__DIR__ . '/../vendor/autoload.php');

use NextBasket\Services\RabbitMQBroker;
use NextBasket\Utils\Log;

$config = [
    'host' => getenv('RABBITMQ_HOST') ?: 'rabbitmq',
    'port' => getenv('RABBITMQ_PORT') ?: 5672,
    'user' => getenv('RABBITMQ_USER') ?: 'root',
    'password' => getenv('RABBITMQ_PASSWORD') ?: 'secret',
];

$log = new Log(__DIR__ . '/app.log');

$consumer = function ($message) use ($log) {
    $log->write($message->body . "\n");
    echo "---> Ingested Message!\n";
};

$channel = 'notifications';
$client = new RabbitMQBroker($config);
$client->retrieve($consumer, $channel);

echo "[+] Started RabbitMQ consumption on '${channel}' channel\n";
while (true) {
    try {
        $client->consume();
    } catch (Exception $e) {}

    sleep(3);
}
