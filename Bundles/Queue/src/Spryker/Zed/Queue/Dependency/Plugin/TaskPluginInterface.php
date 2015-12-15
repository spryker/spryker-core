<?php

namespace Spryker\Zed\Queue\Dependency\Plugin;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface TaskPluginInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getQueueName();

    /**
     * @param QueueMessageTransfer $queueMessage
     */
    public function run(QueueMessageTransfer $queueMessage);

}
