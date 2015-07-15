<?php

namespace SprykerFeature\Zed\Sales\Dependency\Facade;

use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;

interface SalesToOmsInterface
{

    /**
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity();

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

}
