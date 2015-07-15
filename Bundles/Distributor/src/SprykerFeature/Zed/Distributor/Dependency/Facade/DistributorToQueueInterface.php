<?php

namespace SprykerFeature\Zed\Distributor\Dependency\Facade;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface DistributorToQueueInterface
{

    /**
     * @param string $queueName
     * @param QueueMessageTransfer $queueMessage
     */
    public function publishMessage($queueName, QueueMessageTransfer $queueMessage);

}
