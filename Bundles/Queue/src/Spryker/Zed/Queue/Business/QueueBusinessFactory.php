<?php

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Queue\Business\Provider\TaskProvider;
use Spryker\Zed\Queue\Business\Worker\TaskWorker;
use Spryker\Zed\Queue\Business\Model\QueueConnection;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Queue\Business\Model\QueueConnectionInterface;
use Spryker\Zed\Queue\Business\Provider\TaskProviderInterface;
use Spryker\Zed\Queue\Business\Worker\TaskWorkerInterface;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use Spryker\Zed\Queue\QueueConfig;
use Spryker\Zed\Queue\QueueDependencyProvider;

/**
 * @method QueueConfig getConfig()
 */
class QueueBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var QueueConnectionInterface
     */
    protected $queueConnection;

    /**
     * @return QueueConnectionInterface
     */
    public function createQueueConnection()
    {
        if (empty($this->queueConnection)) {
            $this->queueConnection = new QueueConnection(
                $this->getConfig()->getAmqpParameter()
            );
        }

        return $this->queueConnection;
    }

    /**
     * @param string $queueName
     * @param MessengerInterface $messenger
     *
     * @return TaskWorkerInterface
     */
    public function createTaskWorker($queueName, MessengerInterface $messenger)
    {
        $taskWorker = new TaskWorker(
            $this->createQueueConnection(),
            $this->createTaskProvider(),
            $queueName
        );
        $taskWorker->setLogger($messenger);

        return $taskWorker
            ->setErrorQueueName($this->getConfig()->getErrorChannelName())
            ->setMaxMessages($this->getConfig()->getMaxWorkerMessageCount());
    }

    /**
     * @return TaskProviderInterface
     */
    protected function createTaskProvider()
    {
        $taskProvider = new TaskProvider(
            $this->getWorkerTasks()
        );

        return $taskProvider;
    }

    /**
     * @throws \ErrorException
     *
     * @return TaskPluginInterface[]
     */
    protected function getWorkerTasks()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::WORKER_TASKS);
    }

}
