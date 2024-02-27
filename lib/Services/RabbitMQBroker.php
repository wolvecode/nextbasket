<?php

namespace NextBasket\Services;

use Exception;
use NextBasket\Interfaces\MessageBrokerInterface;
use NextBasket\Utils\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQBroker implements MessageBrokerInterface
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var \PhpAmqpLib\Channel\AbstractChannel|\PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected string $defaultQueue = 'notifications';

    /**
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        if ( !$config['host'] && !$config['user'] && !$config['password']) {
            throw new Exception('RabbitMQ misconfigured!');
        }

        // connect to RabbitMQ
        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
    }

    /**
     * @param string $message
     * @param string|null $queue
     * @return void
     */
    public function send(string $message, string $queue = null)
    {
        $this->channel->queue_declare($queue ?? $this->defaultQueue, false, false, false, false);

        $payload = new AMQPMessage($message);
        $this->channel->basic_publish($payload, '',$queue ?? $this->defaultQueue);
    }

    public function retrieve($callback, string $queue = null)
    {
        $this->channel->queue_declare($queue ?? $this->defaultQueue, false, false, false, false);

        $this->channel->basic_consume($queue ?? $this->defaultQueue, '', false, true, false, false, $callback);
    }

    public function consume()
    {
        return $this->channel->consume();
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
