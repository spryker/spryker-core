<?php

namespace SprykerFeature\Zed\Queue\Dependency\Plugin;

use Generated\Shared\Queue\QueueMessageInterface;

interface QueueApiPluginInterface
{

    /**
     * @return string
     */
    public function getReceiverName();

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function send(QueueMessageInterface $queueMessage);
}
