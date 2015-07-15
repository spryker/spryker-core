<?php

namespace SprykerFeature\Zed\Distributor\Business\Builder;

class QueueNameBuilder implements QueueNameBuilderInterface
{

    /**
     * @param string $itemType
     * @param string $receiver
     *
     * @return string
     */
    public function createQueueName($receiver, $itemType)
    {
        return $receiver . '.' . $itemType;
    }

}
