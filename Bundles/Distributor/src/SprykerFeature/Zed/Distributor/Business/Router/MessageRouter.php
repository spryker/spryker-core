<?php

namespace SprykerFeature\Zed\Distributor\Business\Router;

use Generated\Shared\Transfer\QueueMessageTransfer;
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
     * @param QueueMessageTransfer $message
     * @param array $queueList
     */
    public function routeMessage(QueueMessageTransfer $message, array $queueList)
    {
        foreach ($queueList as $queueName) {
            $this->queueFacade->publishMessage($queueName, $message);
        }
    }

}
