<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Unit\SprykerFeature\Zed\Queue\Mock;

use Generated\Shared\Transfer\QueueMessageTransfer;
use PhpAmqpLib\Message\AMQPMessage;
use SprykerFeature\Zed\Queue\Business\Model\QueueConnectionInterface;

class MockQueueConnection implements QueueConnectionInterface
{

    /**
     * @param int $fetchSize
     */
    public function setFetchSize($fetchSize)
    {
    }

    /**
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout)
    {
    }

    /**
     * @param string $queueName
     * @param callable $callback
     * @param string $workerName
     */
    public function listen($queueName, callable $callback, $workerName)
    {
        call_user_func($callback, new AMQPMessage());
    }

    /**
     * @param string $queueName
     * @param QueueMessageTransfer $queueMessage
     */
    public function publish($queueName, QueueMessageTransfer $queueMessage)
    {
    }

    public function stopListen()
    {
    }

    public function purge()
    {
    }

    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message)
    {
    }

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return QueueMessageTransfer
     */
    public function decodeMessage(AMQPMessage $amqpMessage)
    {
        return new QueueMessageTransfer();
    }

}
