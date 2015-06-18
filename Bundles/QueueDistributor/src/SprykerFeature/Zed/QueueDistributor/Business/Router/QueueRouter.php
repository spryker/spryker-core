<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Router;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Facade\QueueDistributorToQueueInterface;

class QueueRouter implements QueueRouterInterface
{

    /**
     * @var QueueDistributorToQueueInterface
     */
    protected $queueFacade;

    /**
     * @param QueueDistributorToQueueInterface $queueFacade
     */
    public function __construct(QueueDistributorToQueueInterface $queueFacade)
    {
        $this->queueFacade = $queueFacade;
    }

    /**
     * @param QueueMessageInterface $message
     * @param array $queueList
     */
    public function routeMessage(QueueMessageInterface $message, array $queueList)
    {
        foreach ($queueList as $queueName) {
            $this->queueFacade->publishMessage($queueName, $message);
        }
    }
}
