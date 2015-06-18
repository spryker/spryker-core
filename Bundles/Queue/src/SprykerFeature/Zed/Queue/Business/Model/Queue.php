<?php

namespace SprykerFeature\Zed\Queue\Business\Model;

use Generated\Shared\Queue\AmqpParameterInterface;
use Generated\Shared\Queue\QueueMessageInterface;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class Queue implements QueueInterface
{

    const QUEUE_PERSISTENT = 'persistent';
    const QUEUE_TRANSIENT = 'transient';
    const DELIVERY_MODE = 'delivery_mode';

    /**
     * @var string
     */
    protected $queueName;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var int
     */
    protected $fetchSize = 1;

    /**
     * @var int
     */
    protected $timeout = 60;

    /**
     * @var bool
     */
    protected $interrupted = false;

    /**
     * @param string $queueName
     * @param AmqpParameterInterface $amqpParameter
     */
    public function __construct($queueName, AmqpParameterInterface $amqpParameter)
    {
        $connection = new AMQPConnection(
            $amqpParameter->getHost(),
            $amqpParameter->getPort(),
            $amqpParameter->getUser(),
            $amqpParameter->getPassword(),
            $amqpParameter->getVhost()
        );

        $this->channel = $connection->channel();
        $this->channel->queue_declare($queueName, false, self::QUEUE_PERSISTENT, false, false);
        $this->queueName = $queueName;
    }

    /**
     * @param int $fetchSize
     */
    public function setFetchSize($fetchSize)
    {
        $this->fetchSize = $fetchSize;
        $this->channel->basic_qos(null, $fetchSize, null);
    }

    /**
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param callable $callback
     * @param string $consumerName
     */
    public function listen(callable $callback, $consumerName)
    {
        $this->interrupted = false;

        $queueName = $this->queueName;
        $consumer_tag = $consumerName;
        $no_local = false;
        $no_ack = false;
        $exclusive = false;
        $nowait = false;
        $ticket = null;
        $arguments = [];

        $this->channel->basic_consume(
            $queueName,
            $consumer_tag,
            $no_local,
            $no_ack,
            $exclusive,
            $nowait,
            $callback,
            $ticket,
            $arguments
        );

        while (count($this->channel->callbacks) && !$this->interrupted) {
            try {
                $this->channel->wait(null, false, $this->timeout);
            } catch (AMQPTimeoutException $timeoutException) {
                // todo: is this the "correct" way of exiting?
                break;
            }
        }
    }

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function publish(QueueMessageInterface $queueMessage)
    {
        $encodedData = $this->encodeMessage($queueMessage);
        $amqpMessage = new AMQPMessage($encodedData, [self::DELIVERY_MODE => 2]);
        $this->channel->basic_publish($amqpMessage, '', $this->queueName);
    }

    public function stopListen()
    {
        $this->interrupted = true;
    }

    public function purge()
    {
        $this->channel->queue_purge();
    }

    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message)
    {
        //$channel = $message->delivery_info['channel'];
        $this->channel->basic_ack($message->delivery_info['delivery_tag']);
    }

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return callable
     */
    public function decodeMessage(AMQPMessage $amqpMessage)
    {
        return unserialize($amqpMessage->body);
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @param QueueMessageInterface $queueMessage
     *
     * @return string
     */
    protected function encodeMessage(QueueMessageInterface $queueMessage)
    {
        return serialize($queueMessage);
    }
}
