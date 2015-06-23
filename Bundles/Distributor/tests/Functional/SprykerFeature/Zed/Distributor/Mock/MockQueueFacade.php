<?php

namespace Functional\SprykerFeature\Zed\Distributor\Mock;

use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerFeature\Zed\Distributor\Dependency\Facade\DistributorToQueueInterface;

class MockQueueFacade implements DistributorToQueueInterface
{

    /**
     * @param string $queueName
     * @param QueueMessageTransfer $queueMessage
     */
    public function publishMessage($queueName, QueueMessageTransfer $queueMessage)
    {
    }
}
