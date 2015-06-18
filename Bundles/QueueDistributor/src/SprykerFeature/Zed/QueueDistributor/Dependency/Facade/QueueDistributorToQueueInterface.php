<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Facade;

use Generated\Shared\Queue\QueueMessageInterface;

interface QueueDistributorToQueueInterface
{

    /**
     * @param string $queueName
     * @param QueueMessageInterface $queueMessage
     */
    public function publishMessage($queueName, QueueMessageInterface $queueMessage);
}
