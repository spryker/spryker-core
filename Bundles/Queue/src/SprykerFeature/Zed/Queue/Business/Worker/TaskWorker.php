<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

use Generated\Shared\Queue\QueueMessageInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SprykerFeature\Zed\Queue\Business\Model\QueueInterface;
use SprykerFeature\Zed\Queue\Business\provider\TaskProviderInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskWarmUpPluginInterface;

class TaskWorker implements LoggerAwareInterface, TaskWorkerInterface
{

    use LoggerAwareTrait;

    /**
     * @var QueueInterface
     */
    protected $subscribedQueue;

    /**
     * @var TaskPluginInterface|TaskWarmUpPluginInterface
     */
    protected $task = null;

    /**
     * @var int
     */
    protected $maxMessages = 0;

    /**
     * @var int
     */
    protected $processedMessages = 0;

    /**
     * @var QueueInterface
     */
    protected $responseQueue = null;

    /**
     * @var QueueInterface
     */
    protected $errorQueue = null;

    /**
     * @var ErrorHandlerInterface
     */
    protected $errorHandler;

    /**
     * @var TaskProviderInterface
     */
    protected $taskProvider;

    /**
     * @param QueueInterface $subscribedQueue
     * @param TaskProviderInterface $taskProvider
     */
    public function __construct(
        QueueInterface $subscribedQueue,
        TaskProviderInterface $taskProvider
    ) {
        $this->subscribedQueue = $subscribedQueue;
        $this->taskProvider = $taskProvider;
    }

    /**
     * @param QueueInterface $responseQueue
     *
     * @return $this
     */
    public function setResponseQueue(QueueInterface $responseQueue)
    {
        $this->responseQueue = $responseQueue;

        return $this;
    }

    /**
     * @param QueueInterface $errorQueue
     *
     * @return $this
     */
    public function setErrorQueue(QueueInterface $errorQueue)
    {
        $this->errorQueue = $errorQueue;

        return $this;
    }

    /**
     * @param int $maxMessages
     *
     * @return $this
     */
    public function setMaxMessages($maxMessages)
    {
        $this->maxMessages = $maxMessages;

        return $this;
    }

    /**
     * @param ErrorHandlerInterface $errorHandler
     *
     * @return $this
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;

        return $this;
    }

    /**
     * @param int $timeout
     * @param int $fetchSize
     */
    public function work($timeout = 10, $fetchSize = 10)
    {
        $this->task = $this->initializeTask();

        $worker = $this;

        $callback = function ($amqpMessage) use ($worker) {
            $queueMessage = $this->subscribedQueue->decodeMessage($amqpMessage);
            $worker->processMessage($queueMessage);
            $this->subscribedQueue->acknowledge($amqpMessage);
        };

        $this->subscribedQueue->setTimeout($timeout);
        $this->subscribedQueue->setFetchSize($fetchSize);
        $this->subscribedQueue->listen($callback, $this->task->getName());
    }

    /**
     * @return TaskPluginInterface
     */
    protected function initializeTask()
    {
        $queueName = $this->subscribedQueue->getQueueName();
        $task = $this->taskProvider->getTaskByQueueName($queueName);

        if ($task instanceof LoggerAwareInterface) {
            $task->setLogger($this->logger);
        }
        if ($task instanceof TaskWarmUpPluginInterface) {
            $task->warmUp($this->logger);
        }

        return $task;
    }

    /**
     * @param QueueMessageInterface $queueMessage
     *
     * @return bool
     */
    protected function processMessage(QueueMessageInterface $queueMessage)
    {
        try {
            $this->task->run($queueMessage);
            $this->logger->debug(
                sprintf(
                    '%s: finished task %s',
                    $queueMessage->getId(),
                    $this->task->getName()
                )
            );
        } catch (\Exception $exception) {
             $this->handleError($queueMessage, $exception);

             return false;
        }

        if (!is_null($this->responseQueue)) {
            $this->responseQueue->publish($queueMessage);
        }

        $this->processedMessages++;
        if ($this->processedMessages >= $this->maxMessages) {
            $this->subscribedQueue->stopListen();
        }

        return true;
    }

    /**
     * @param QueueMessageInterface $queueMessage
     * @param \Exception $exception
     *
     * @return bool
     */
    protected function handleError(QueueMessageInterface $queueMessage, \Exception $exception)
    {
        $queueMessage->setError($exception->getMessage());
        $this->errorQueue->publish($queueMessage);

        $this->logger->error(
            sprintf(
                '%s: %s%s%s',
                $queueMessage->getId(),
                $exception->getMessage(),
                PHP_EOL,
                $exception->getTraceAsString()
            )
        );

        if (!is_null($this->errorHandler)) {
            $this->errorHandler->handleError($exception);
        }
    }
}
