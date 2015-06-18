<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Provider;

interface ItemQueueProviderInterface
{
    /**
     * @param string $itemType
     *
     * @return array
     */
    public function getAllQueueForType($itemType);
}
