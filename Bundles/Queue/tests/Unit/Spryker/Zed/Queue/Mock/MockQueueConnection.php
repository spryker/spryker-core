<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Unit\Spryker\Zed\Queue\Mock;

use Generated\Shared\Transfer\QueueMessageTransfer;
use PhpAmqpLib\Message\AMQPMessage;
use Spryker\Zed\Queue\Business\Model\QueueConnectionInterface;

class MockQueueConnection implements QueueConnectionInterface
{

    /**
     * @param int $fetchSize
     *
     * @return void
     */
    public function setFetchSize($fetchSize)
    {
    }

    /**
     * @param int $timeout Timeout in seconds
     *
     * @return void
     */
    public function setTimeout($timeout)
    {
    }

    /**
     * @param string $queueName
     * @param callable $callback
     * @param string $workerName
     *
     * @return void
     */
    public function listen($queueName, callable $callback, $workerName)
    {
        call_user_func($callback, new AMQPMessage());
    }

    /**
     * @param string $queueName
     * @param QueueMessageTransfer $queueMessage
     *
     * @return void
     */
    public function publish($queueName, QueueMessageTransfer $queueMessage)
    {
    }

    /**
     * @return void
     */
    public function stopListen()
    {
    }

    /**
     * @return void
     */
    public function purge()
    {
    }

    /**
     * @param AMQPMessage $message
     *
     * @return void
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
