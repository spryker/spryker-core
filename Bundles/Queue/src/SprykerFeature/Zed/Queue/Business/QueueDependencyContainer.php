<?php

namespace SprykerFeature\Zed\Queue\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\QueueBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Queue\Business\Model\QueueConnectionInterface;
use SprykerFeature\Zed\Queue\Business\Provider\TaskProviderInterface;
use SprykerFeature\Zed\Queue\Business\Worker\TaskWorkerInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use SprykerFeature\Zed\Queue\QueueConfig;
use SprykerFeature\Zed\Queue\QueueDependencyProvider;

/**
 * @method QueueBusiness getFactory()
 * @method QueueConfig getConfig()
 */
class QueueDependencyContainer extends AbstractBusinessDependencyContainer
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
            $this->queueConnection = $this->getFactory()->createModelQueueConnection(
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
        $taskWorker = $this->getFactory()->createWorkerTaskWorker(
            $this->createQueueConnection(),
            $this->createTaskProvider(),
            $queueName
        );
        $taskWorker->setLogger($messenger);

        return $taskWorker
            ->setErrorQueueName($this->getConfig()->getErrorChannelName())
            ->setMaxMessages($this->getConfig()->getMaxWorkerMessageCount())
        ;
    }

    /**
     * @return TaskProviderInterface
     */
    protected function createTaskProvider()
    {
        $taskProvider = $this->getFactory()->createProviderTaskProvider(
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
