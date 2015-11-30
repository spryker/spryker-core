<?php

namespace SprykerFeature\Zed\Distributor\Business\Router;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface MessageRouterInterface
{

    /**
     * @param QueueMessageTransfer $message
     * @param array $queueList
     */
    public function routeMessage(QueueMessageTransfer $message, array $queueList);

}
