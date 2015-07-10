<?php

namespace SprykerFeature\Zed\Distributor\Business\Builder;

interface QueueNameBuilderInterface
{

    /**
     * @param string $itemType
     * @param string $receiver
     *
     * @return string
     */
    public function createQueueName($receiver, $itemType);

}
