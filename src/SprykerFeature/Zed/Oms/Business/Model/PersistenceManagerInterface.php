<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

/**
 * Interface PersistenceManagerInterface
 * @package SprykerFeature\Zed\Oms\Business\Model
 */
interface PersistenceManagerInterface
{
    /**
     * @param string $statusName
     * @return \SprykerFeature_Zed_Oms_Persistence_SpyOmsOrderItemStatus
     */
    public function getStatusEntity($statusName);

    /**
     * @param string $processName
     * @return \SprykerFeature_Zed_Oms_Persistence_SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * @return \SprykerFeature_Zed_Oms_Persistence_SpyOmsOrderItemStatus
     */
    public function getInitialStatusEntity();
}
