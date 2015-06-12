<?php

namespace ProjectA\Queue;

use ProjectA\Queue\Api;
use ProjectA\Queue\DataObject;
use ProjectA\Queue\QueueMessage;
use PhpAmqpLib\Message\AMQPMessage;
use ProjectA\Queue\ErrorHandlerInterface;
use ProjectA\Queue\Task\TaskInterface;
use ProjectA\Queue\Task\TaskPostRunInterface;
use ProjectA\Queue\Task\TaskPreRunInterface;
use ProjectA\Queue\Task\TaskWarmUpInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Worker implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var TaskInterface|TaskWarmUpInterface
     */
    protected $task = null;

    /**
     * @var string
     */
    protected $taskName;

    /**
     * @var int
     */
    protected $maxMessages = 0;

    /**
     * @var int
     */
    protected $processedMessages = 0;

    /**
     * @var string
     */
    protected $inputQueueName = null;

    /**
     * @var string
     */
    protected $responseQueueName = null;

    /**
     * @var string
     */
    protected $errorQueueName = null;

    /**
     * @var ErrorHandlerInterface
     */
    protected $errorHandler;

    /**
     * @param $api
     * @param TaskInterface $task
     */
    public function __construct($api, TaskInterface $task)
    {
        $this->api = $api;
        if ($task instanceof TaskWarmUpInterface) {
            $task->warmUp();
        }
        $this->task = $task;
        $this->taskName = $task->getName();
    }

    /**
     * @param string $responseQueueName
     * @return Worker
     */
    public function setResponseQueueName($responseQueueName)
    {
        $this->responseQueueName = $responseQueueName;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseQueueName()
    {
        return $this->responseQueueName;
    }

    /**
     * @param string $errorQueueName
     * @return Worker
     */
    public function setErrorQueueName($errorQueueName)
    {
        $this->errorQueueName = $errorQueueName;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorQueueName()
    {
        return $this->errorQueueName;
    }

    /**
     * @param string $inputQueueName
     * @return Worker
     */
    public function setInputQueueName($inputQueueName)
    {
        $this->inputQueueName = $inputQueueName;
        return $this;
    }

    /**
     * @return string
     */
    public function getInputQueueName()
    {
        return $this->inputQueueName;
    }

    /**
     * @param $maxMessages
     */
    public function setMaxMessages($maxMessages)
    {
        $this->maxMessages = $maxMessages;
    }

    /**
     * @param ErrorHandlerInterface $errorHandler
     * @return Worker
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
        return $this;
    }

    /**
     * @return ErrorHandlerInterface
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * @param int $timeout
     * @param int $fetchSize
     */
    public function work($timeout = 10, $fetchSize = 10)
    {
        $worker = $this;

        if ($this->task instanceof LoggerAwareInterface) {
            $this->task->setLogger($this->logger);
        }

        $this->api->listen(
            $this->inputQueueName,
            function ($queueMessage) use ($worker) {
                $worker->call($queueMessage);
            },
            $timeout,
            $fetchSize,
            'Worker ' . $this->taskName
        );
    }

    /**
     * @param QueueMessage $queueMessage
     * @return bool
     */
    protected function call(QueueMessage $queueMessage)
    {
        try {
            $this->executeTask($queueMessage->getDataObject());
            $this->logger->debug(sprintf('%s: finished task %s', $queueMessage->getId(), $this->task->getName()));
        } catch (\Exception $e) {
            $queueMessage->setError($e->getMessage());
            $this->api->publish($this->errorQueueName, $queueMessage);
            $this->api->acknowledge($queueMessage);

            $this->logger->error(
                sprintf('%s: %s%s%s', $queueMessage->getId(), $e->getMessage(), PHP_EOL, $e->getTraceAsString())
            );

            if (!is_null($this->errorHandler)) {
                $this->errorHandler->handleError($e);
            }

            return false;
        }

        if (!is_null($this->responseQueueName)) {
            $this->api->publish($this->responseQueueName, $queueMessage);
        }

        $this->api->acknowledge($queueMessage);

        $this->processedMessages++;
        if ($this->processedMessages >= $this->maxMessages) {
            // Its not working!!!
            // $queue->stopListen();
        }

        return true;
    }

    /**
     * @param DataObject $dataObject
     */
    protected function executeTask(DataObject $dataObject)
    {
        if ($this->task instanceof TaskPreRunInterface) {
            $this->task->preRun($dataObject);
        }

        $this->task->run($dataObject);

        if ($this->task instanceof TaskPostRunInterface) {
            $this->task->postRun($dataObject);
        }
    }

} 
