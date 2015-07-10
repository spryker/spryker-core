<?php

namespace SprykerFeature\Zed\Distributor\Business\Router;

use Generated\Shared\Distributor\QueueMessageInterface;
use SprykerFeature\Zed\Distributor\Dependency\Facade\DistributorToQueueInterface;

class MessageRouter implements MessageRouterInterface
{

    /**
     * @var DistributorToQueueInterface
     */
    protected $queueFacade;

    /**
     * @param DistributorToQueueInterface $queueFacade
     */
    public function __construct(DistributorToQueueInterface $queueFacade)
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
