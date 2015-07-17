<?php

namespace SprykerFeature\Zed\Queue\Dependency\Plugin;

use Generated\Shared\Queue\QueueMessageInterface;

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
     * @param QueueMessageInterface $queueMessage
     */
    public function run(QueueMessageInterface $queueMessage);

}
