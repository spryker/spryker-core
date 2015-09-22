<?php

namespace SprykerFeature\Zed\Refund\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;

interface RefundToOmsInterface
{

    /**
     * @param string $eventId
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = []);

}
