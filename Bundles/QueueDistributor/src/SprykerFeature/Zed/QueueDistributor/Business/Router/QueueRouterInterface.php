<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Router;

use Generated\Shared\Queue\QueueMessageInterface;

interface QueueRouterInterface
{
    /**
     * @param QueueMessageInterface $message
     * @param array $queueList
     */
    public function routeMessage(QueueMessageInterface $message, array $queueList);
}
