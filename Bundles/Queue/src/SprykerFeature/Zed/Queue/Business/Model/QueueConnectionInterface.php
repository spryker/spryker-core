<?php

namespace SprykerFeature\Zed\Queue\Business\Model;

use Generated\Shared\Queue\QueueMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

interface QueueConnectionInterface
{

    /**
     * @param int $fetchSize
     */
    public function setFetchSize($fetchSize);

    /**
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout);

    /**
     * @param string $queueName
     * @param callable $callback
     * @param string $workerName
     */
    public function listen($queueName, callable $callback, $workerName);

    /**
     * @param string $queueName
     * @param QueueMessageInterface $queueMessage
     */
    public function publish($queueName, QueueMessageInterface $queueMessage);

    public function stopListen();

    public function purge();

    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message);

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return QueueMessageInterface
     */
    public function decodeMessage(AMQPMessage $amqpMessage);

}
