<?php

namespace Spryker\Zed\Queue\Business\Worker;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Spryker\Zed\Queue\Business\Model\QueueConnectionInterface;
use Spryker\Zed\Queue\Business\Provider\TaskProviderInterface;
use Spryker\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use Spryker\Zed\Queue\Dependency\Plugin\TaskWarmUpPluginInterface;

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
     * @return self
     */
    public function setResponseQueueName($responseQueueName)
    {
        $this->responseQueueName = $responseQueueName;

        return $this;
    }

    /**
     * @param string $errorQueueName
     *
     * @return self
     */
    public function setErrorQueueName($errorQueueName)
    {
        $this->errorQueueName = $errorQueueName;

        return $this;
    }

    /**
     * @param int $maxMessages
     *
     * @return self
     */
    public function setMaxMessages($maxMessages)
    {
        $this->maxMessages = $maxMessages;

        return $this;
    }

    /**
     * @param ErrorHandlerInterface $errorHandler
     *
     * @return self
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;

        return $this;
    }

    /**
     * @param int $timeout
     * @param int $fetchSize
     *
     * @return void
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
     * @param QueueMessageTransfer $queueMessage
     *
     * @return bool
     */
    protected function processMessage(QueueMessageTransfer $queueMessage)
    {
        try {
            $this->task->run($queueMessage);
            if ($this->logger !== null) {
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

        if ($this->responseQueueName !== null) {
            $this->responseQueueName->publish($this->responseQueueName, $queueMessage);
        }

        $this->processedMessages++;
        if ($this->processedMessages >= $this->maxMessages) {
            $this->queueConnection->stopListen();
        }

        return true;
    }

    /**
     * @param QueueMessageTransfer $queueMessage
     * @param \Exception $exception
     *
     * @return bool
     */
    protected function handleError(QueueMessageTransfer $queueMessage, \Exception $exception)
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
        if ($this->errorQueueName !== null) {
            $this->queueConnection->publish($this->errorQueueName, $queueMessage);
        }
        if ($this->errorHandler !== null) {
            $this->errorHandler->handleError($exception);
        }
    }

}
