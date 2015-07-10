<?php

namespace SprykerFeature\Zed\Queue\Business\Model;

use Generated\Shared\Queue\AmqpParameterInterface;
use Generated\Shared\Queue\QueueMessageInterface;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class QueueConnection implements QueueConnectionInterface
{

    const QUEUE_PERSISTENT = 'persistent';
    const QUEUE_TRANSIENT = 'transient';
    const DELIVERY_MODE = 'delivery_mode';

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
     * @var array
     */
    protected $queues = [];

    /**
     * @param AmqpParameterInterface $amqpParameter
     */
    public function __construct(AmqpParameterInterface $amqpParameter)
    {
        $connection = new AMQPConnection(
            $amqpParameter->getHost(),
            $amqpParameter->getPort(),
            $amqpParameter->getUser(),
            $amqpParameter->getPassword(),
            $amqpParameter->getVhost()
        );

        $this->channel = $connection->channel();
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
     * @param string $queueName
     * @param callable $callback
     * @param string $workerName
     */
    public function listen(
        $queueName,
        callable $callback,
        $workerName
    ) {
        $this->declareQueue($queueName);
        $this->interrupted = false;

        $consumer_tag = $workerName;
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
                break;
            }
        }
    }

    /**
     * @param string $queueName
     * @param QueueMessageInterface $queueMessage
     */
    public function publish($queueName, QueueMessageInterface $queueMessage)
    {
        $this->declareQueue($queueName);

        $encodedData = $this->encodeMessage($queueMessage);
        $amqpMessage = new AMQPMessage($encodedData, [self::DELIVERY_MODE => 2]);
        $this->channel->basic_publish($amqpMessage, '', $queueName);
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
        $this->channel->basic_ack($message->delivery_info['delivery_tag']);
    }

    /**
     * @param AMQPMessage $amqpMessage
     *
     * @return QueueMessageInterface
     */
    public function decodeMessage(AMQPMessage $amqpMessage)
    {
        return unserialize($amqpMessage->body);
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

    /**
     * @param string $queueName
     */
    protected function declareQueue($queueName)
    {
        if (!array_key_exists($queueName, $this->queues)) {
            $this->channel->queue_declare(
                $queueName,
                false,
                self::QUEUE_PERSISTENT,
                false,
                false
            );
            $this->queues[$queueName] = true;
        }
    }

}
