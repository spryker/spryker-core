<?php

namespace ProjectA\Queue;

use ProjectA\Queue\ErrorWorker;
use ProjectA\Queue\Worker;
use ProjectA\Queue\QueueMessage;
use PhpAmqpLib\Message\AMQPMessage;
use ProjectA\Queue\Task\TaskInterface;

class Api
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @var QueueServer[]
     */
    protected static $queueBuffer = null;

    /**
     * @param $queueName
     * @return Queue
     */
    protected function createQueue($queueName)
    {
        if (!isset(self::$queueBuffer[$queueName])) {
            $queueServer = $this->createQueueServer();
            $queueServer->declareQueue($queueName);
            self::$queueBuffer[$queueName] = $queueServer;
        }

        $queue = self::$queueBuffer[$queueName]->getQueue($queueName);

        return $queue;
    }

    /**
     * @param $data
     * @return string
     */
    protected function encodeData($data)
    {
        if (false === is_scalar($data)) {
            $data = serialize($data);
            return $data;
        }

        return $data;
    }

    /**
     * @return QueueServer
     */
    protected function createQueueServer()
    {
        $queueServer = new QueueServer(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost']
        );
        return $queueServer;
    }
    /**
     * @param $queueName
     * @param QueueMessage $queueMessage
     */
    public function publish($queueName, QueueMessage $queueMessage)
    {
        $queue = $this->createQueue($queueName);
        $data = $this->encodeData($queueMessage);
        $queue->publish($data);
    }

    /**
     * @param $queueName
     * @param callable $callback
     * @param $timeout
     * @param $fetchSize
     * @param $name
     */
    public function listen($queueName, callable $callback, $timeout, $fetchSize, $name)
    {
        $queue = $this->createQueue($queueName);

        $queue->setTimeout($timeout);
        $queue->setFetchSize($fetchSize);

        $queue->listen($callback, $name);
    }

    /**
     * @param QueueMessage $message
     */
    public function acknowledge(QueueMessage $message)
    {
        $amqpMessage = $message->getMessage();
        $amqpMessage->delivery_info['channel']->basic_ack($amqpMessage->delivery_info['delivery_tag']);
    }

    /**
     * @param $queueName
     */
    public function purge($queueName)
    {
        $queue = $this->createQueue($queueName);
        $queue->purge();
    }

    /**
     * @param $queueName
     */
    public function stopListen($queueName)
    {
        $queue = $this->createQueue($queueName);
        $queue->stopListen();
    }

    /**
     * @param $inputQueueName
     * @param $responseQueueName
     * @param $errorQueueName
     * @param TaskInterface $task
     * @return Worker
     */
    public function getWorker($inputQueueName, $responseQueueName, $errorQueueName, TaskInterface $task)
    {
        $worker = (new Worker($this, $task))
            ->setInputQueueName($inputQueueName)
            ->setResponseQueueName($responseQueueName)
            ->setErrorQueueName($errorQueueName)
        ;

        return $worker;
    }

    /**
     * @param $inputQueueName
     * @param $taskName
     * @return ErrorWorker
     */
    public function getErrorWorker($inputQueueName, $taskName)
    {
        $worker = (new ErrorWorker($this, $taskName))
            ->setInputQueueName($inputQueueName)
        ;

        return $worker;
    }
}
