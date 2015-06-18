<?php

namespace SprykerFeature\Zed\Queue\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\QueueBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Queue\Business\Model\QueueInterface;
use SprykerFeature\Zed\Queue\Business\Provider\TaskProviderInterface;
use SprykerFeature\Zed\Queue\Business\Worker\TaskWorkerInterface;
use SprykerFeature\Zed\Queue\QueueConfig;

/**
 * @method QueueBusiness getFactory()
 * @method QueueConfig getConfig()
 */
class QueueDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var QueueInterface[]
     */
    protected $queues = [];

    /**
     * @param string $queueName
     *
     * @return QueueInterface
     */
    public function createQueueConnection($queueName)
    {
        if (!isset($this->queues[$queueName])) {
            $this->queues[$queueName] = $this->getFactory()->createModelQueue(
                $queueName,
                $this->getConfig()->getAmqpParameter()
            );
        }

        return $this->queues[$queueName];
    }

    /**
     * @param $queueName
     *
     * @return TaskWorkerInterface
     */
    public function createTaskWorker($queueName)
    {
        $taskWorker = $this->getFactory()->createWorkerTaskWorker(
            $this->createQueueConnection($queueName),
            $this->createTaskProvider()
        );
        return $taskWorker
            ->setErrorQueue($this->createErrorQueue())
            ->setMaxMessages($this->getConfig()->getMaxWorkerMessageCount())
        ;
    }

    /**
     * @return TaskProviderInterface
     */
    protected function createTaskProvider()
    {
        return $this->getFactory()->createProviderTaskProvider(
            $this->getConfig()->getWorkerTasks()
        );
    }

    /**
     * @return QueueInterface
     */
    protected function createErrorQueue()
    {
        return $this->createQueueConnection(
            $this->getConfig()->getErrorChannelName()
        );
    }
}
