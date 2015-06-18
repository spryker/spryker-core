<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Builder;

interface QueueNameBuilderInterface
{
    /**
     * @param string $itemType
     * @param string $receiver
     *
     * @return string
     */
    public function createQueueName($itemType, $receiver);
}
