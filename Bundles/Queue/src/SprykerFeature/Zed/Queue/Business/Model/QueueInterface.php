<?php

namespace SprykerFeature\Zed\Queue\Business\Model;

use Generated\Shared\Queue\QueueMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

interface QueueInterface
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
     * @param QueueMessageInterface $queueMessage
     */
    public function publish(QueueMessageInterface $queueMessage);

    /**
     * @param callable $callback
     * @param string $consumerName
     */
    public function listen(callable $callback, $consumerName);

    /**
     * @param AMQPMessage $amqpMessage
     */
    public function acknowledge(AMQPMessage $amqpMessage);

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return QueueMessageInterface $queueMessage
     */
    public function decodeMessage(AMQPMessage $amqpMessage);

    public function stopListen();

    public function purge();

    /**
     * @return string
     */
    public function getQueueName();
}
