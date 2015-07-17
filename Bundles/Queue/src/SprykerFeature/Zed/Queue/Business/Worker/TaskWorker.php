<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

use Generated\Shared\Queue\QueueMessageInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SprykerFeature\Zed\Queue\Business\Model\QueueConnectionInterface;
use SprykerFeature\Zed\Queue\Business\Provider\TaskProviderInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskWarmUpPluginInterface;

class TaskWorker implements LoggerAwareInterface, TaskWorkerInterface
{

    use LoggerAwareTrait;

    /**
     * @var QueueConnectionInterface
     */
    protected $queueConnection;

    /**
     * @var TaskProviderInterface
     */
    protected $taskProvider;

    /**
     * @var string
     */
    protected $queueName;

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
     * @var QueueConnectionInterface
     */
    protected $responseQueueName = null;

    /**
     * @var QueueConnectionInterface
     */
    protected $errorQueueName = null;

    /**
     * @var ErrorHandlerInterface
     */
    protected $errorHandler;

    /**
     * @param QueueConnectionInterface $queueConnection
     * @param TaskProviderInterface $taskProvider
     * @param string $subscribedQueueName
     */
    public function __construct(
        QueueConnectionInterface $queueConnection,
        TaskProviderInterface $taskProvider,
        $subscribedQueueName
    ) {
        $this->queueConnection = $queueConnection;
        $this->taskProvider = $taskProvider;
        $this->queueName = $subscribedQueueName;
    }

    /**
     * @param string $responseQueueName
     *
     * @return $this
     */
    public function setResponseQueueName($responseQueueName)
    {
        $this->responseQueueName = $responseQueueName;

        return $this;
    }

    /**
     * @param string $errorQueueName
     *
     * @return $this
     */
    public function setErrorQueueName($errorQueueName)
    {
        $this->errorQueueName = $errorQueueName;

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
            $queueMessage = $this->queueConnection->decodeMessage($amqpMessage);
            $worker->processMessage($queueMessage);
            $this->queueConnection->acknowledge($amqpMessage);
        };

        $this->queueConnection->setTimeout($timeout);
        $this->queueConnection->setFetchSize($fetchSize);
        $this->queueConnection->listen($this->queueName, $callback, $this->task->getName());
    }

    /**
     * @return TaskPluginInterface
     */
    protected function initializeTask()
    {
        $task = $this->taskProvider->getTaskByQueueName($this->queueName);

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
            if (!is_null($this->logger)) {
                $this->logger->info(
                    sprintf(
                        '%s: finished task %s',
                        $queueMessage->getId(),
                        $this->task->getName()
                    )
                );
            }
        } catch (\Exception $exception) {
             $this->handleError($queueMessage, $exception);

             return false;
        }

        if (!is_null($this->responseQueueName)) {
            $this->responseQueueName->publish($this->responseQueueName, $queueMessage);
        }

        $this->processedMessages++;
        if ($this->processedMessages >= $this->maxMessages) {
            $this->queueConnection->stopListen();
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
        $this->logger->error(
            sprintf(
                '%s: %s%s%s',
                $queueMessage->getId(),
                $exception->getMessage(),
                PHP_EOL,
                $exception->getTraceAsString()
            )
        );

        $queueMessage->setError($exception->getMessage());
        if (!is_null($this->errorQueueName)) {
            $this->queueConnection->publish($this->errorQueueName, $queueMessage);
        }
        if (!is_null($this->errorHandler)) {
            $this->errorHandler->handleError($exception);
        }
    }

}
