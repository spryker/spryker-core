<?php

namespace SprykerFeature\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;

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

    /**
     * @param OrderTransfer $transferOrder
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $transferOrder);

}
