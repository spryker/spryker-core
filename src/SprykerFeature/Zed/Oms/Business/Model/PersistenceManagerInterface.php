<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;

interface PersistenceManagerInterface
{
    /**
     * @param string $statusName
     *
     * @return SpyOmsOrderItemStatus
     */
    public function getStatusEntity($statusName);

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * @return SpyOmsOrderItemStatus
     */
    public function getInitialStatusEntity();
}
