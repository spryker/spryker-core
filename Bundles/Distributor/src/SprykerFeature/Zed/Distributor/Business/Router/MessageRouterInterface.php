<?php

namespace SprykerFeature\Zed\Distributor\Business\Router;

use Generated\Shared\Distributor\QueueMessageInterface;

interface MessageRouterInterface
{

    /**
     * @param QueueMessageInterface $message
     * @param array $queueList
     */
    public function routeMessage(QueueMessageInterface $message, array $queueList);

}
