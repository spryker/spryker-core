<?php

namespace SprykerFeature\Zed\Distributor\Business\Provider;

interface ItemQueueProviderInterface
{

    /**
     * @param string $itemType
     *
     * @return array
     */
    public function getAllQueuesForType($itemType);

}
