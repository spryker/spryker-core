<?php

namespace ProjectA\Queue;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;

class QueueServer
{

    const QUEUE_PERSISTENT = 'persistent';
    const QUEUE_TRANSIENT = 'transient';

    /**
     * @var AMQPConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $vhost
     */
    public function __construct($host, $port, $user, $password, $vhost)
    {
        $this->connection = new AMQPConnection($host, $port, $user, $password, $vhost);
        $this->channel = $this->connection->channel();
    }

    /**
     * @param $name
     * @param string $persistentQueue
     */
    public function declareQueue($name, $persistentQueue = self::QUEUE_PERSISTENT)
    {
        $persistent = $persistentQueue == self::QUEUE_PERSISTENT;
        $this->channel->queue_declare($name, false, $persistent, false, false);
    }

    /**
     * @param $name
     * @return bool
     * @todo: fill with code
     */
    public function queueDeclared($name)
    {
        return false;
    }

    /**
     * @param $queueName
     * @return Queue
     */
    public function getQueue($queueName)
    {
        return new Queue($queueName, $this->channel);
    }

} 
