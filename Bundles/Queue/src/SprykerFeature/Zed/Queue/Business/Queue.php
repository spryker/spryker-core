<?php

namespace ProjectA\Queue;

use ProjectA\Queue\QueueMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class Queue
{

    /**
     * @var string
     */
    protected $name;

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
     * @var Callable
     */
    protected $currentCallable;

    /**
     * @param $queueName
     * @param AMQPChannel $channel
     */
    public function __construct($queueName, AMQPChannel $channel)
    {
        $this->name = $queueName;
        $this->channel = $channel;
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
     * @param $name
     */
    public function listen(callable $callback, $name)
    {
        $this->interrupted = false;

        $this->currentCallable = $callback;

        $queue = $this->name;
        $consumer_tag = $name;
        $no_local = false;
        $no_ack = false;
        $exclusive = false;
        $nowait = false;
        $callback = array($this, 'decodeMessage');
        $ticket = null;
        $arguments = array();

        $this->channel->basic_consume(
            $queue,
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

    public function decodeMessage(AMQPMessage $msg)
    {
        /* @var $queueMessage QueueMessage */
        $queueMessage = unserialize($msg->body);
        $queueMessage->setMessage($msg);
        call_user_func($this->currentCallable, $queueMessage);
    }

    /**
     * @param $data
     */
    public function publish($data)
    {
        // array('delivery_mode' => 2)   -->   persistent message
        $message = new AMQPMessage($data, array('delivery_mode' => 2));
        // ignoring routing for now
        $this->channel->basic_publish($message, '', $this->name);
    }

    public function stopListen()
    {
        $this->interrupted = true;
    }

    public function purge()
    {
        $this->channel->queue_purge();
    }

}
