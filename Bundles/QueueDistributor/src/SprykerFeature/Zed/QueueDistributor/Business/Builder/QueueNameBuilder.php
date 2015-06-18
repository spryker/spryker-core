<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Builder;

class QueueNameBuilder implements QueueNameBuilderInterface
{

    /**
     * @param string $itemType
     * @param string $receiver
     *
     * @return string
     */
    public function createQueueName($itemType, $receiver)
    {
        return $receiver . '.' . $itemType;
    }
}
