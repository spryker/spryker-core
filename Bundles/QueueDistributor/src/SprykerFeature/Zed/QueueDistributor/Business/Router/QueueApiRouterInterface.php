<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Router;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\QueueApiPluginInterface;

interface QueueApiRouterInterface
{
    /**
     * @param QueueApiPluginInterface $queueApi
     */
    public function addQueueApi(QueueApiPluginInterface $queueApi);

    /**
     * @param QueueMessageInterface $message
     * @param array $receiverList
     */
    public function routeMessage(QueueMessageInterface $message, array $receiverList);
}
