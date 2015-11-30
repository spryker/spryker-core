<?php

namespace SprykerFeature\Zed\Queue\Business\Model;

use Generated\Shared\Transfer\QueueMessageTransfer;
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
     * @param QueueMessageTransfer $queueMessage
     */
    public function publish($queueName, QueueMessageTransfer $queueMessage);

    public function stopListen();

    public function purge();

    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message);

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return QueueMessageTransfer
     */
    public function decodeMessage(AMQPMessage $amqpMessage);

}
