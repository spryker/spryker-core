<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;

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
