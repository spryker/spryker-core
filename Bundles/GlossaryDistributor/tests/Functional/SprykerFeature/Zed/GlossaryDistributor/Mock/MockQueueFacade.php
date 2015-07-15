<?php

namespace Functional\SprykerFeature\Zed\GlossaryDistributor\Mock;

use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerFeature\Zed\Distributor\Dependency\Facade\DistributorToQueueInterface;

class MockQueueFacade implements DistributorToQueueInterface
{

    /**
     * @var array
     */
    private $publishedMessages = [];

    /**
     * @param string $queueName
     * @param QueueMessageTransfer $queueMessage
     */
    public function publishMessage($queueName, QueueMessageTransfer $queueMessage)
    {
        $this->publishedMessages[$queueName] = $queueMessage;
    }

    /**
     * @return array
     */
    public function getPublishedMessages()
    {
        return $this->publishedMessages;
    }

}
