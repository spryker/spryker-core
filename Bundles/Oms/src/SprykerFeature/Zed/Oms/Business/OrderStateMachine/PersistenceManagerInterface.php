<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;

interface PersistenceManagerInterface
{

    /**
     * @param string $stateName
     *
     * @return SpyOmsOrderItemState
     */
    public function getStateEntity($stateName);

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity();

}
