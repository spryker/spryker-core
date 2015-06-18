<?php

namespace SprykerFeature\Zed\Queue\Business;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method QueueDependencyContainer getDependencyContainer()
 */
class QueueFacade extends AbstractFacade
{

    /**
     * @param string $queueName
     * @param QueueMessageInterface $queueMessage
     */
    public function publishMessage($queueName, QueueMessageInterface $queueMessage)
    {
        $this->getDependencyContainer()
            ->createQueueConnection($queueName)
            ->publish($queueMessage)
        ;
    }

    /**
     * @param string $queueName
     * @param int $timeout
     * @param int $fetchSize
     */
    public function startWorker($queueName, $timeout = 10, $fetchSize = 10)
    {
        $this->getDependencyContainer()
            ->createTaskWorker($queueName)
            ->work($timeout, $fetchSize)
        ;
    }
}
