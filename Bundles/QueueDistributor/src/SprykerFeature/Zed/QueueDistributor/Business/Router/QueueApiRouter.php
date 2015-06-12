<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Router;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerFeature\Zed\Queue\Dependency\Plugin\QueueApiPluginInterface;

class QueueApiRouter implements QueueApiRouterInterface
{

    /**
     * @var array|QueueApiPluginInterface[]
     */
    protected $queueApis = [];

    /**
     * @param QueueApiPluginInterface $queueApi
     */
    public function addQueueApi(QueueApiPluginInterface $queueApi)
    {
        $this->queueApis[$queueApi->getReceiverName()] = $queueApi;
    }

    /**
     * @param QueueMessageInterface $message
     * @param array $receiverList
     */
    public function routeMessage(QueueMessageInterface $message, array $receiverList)
    {
        foreach ($receiverList as $receiver) {
            $queueApi = $this->queueApis[$receiver];
            $queueApi->send($message);
        }
    }
}
