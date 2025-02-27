<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\OmsOrderItemStateTransfer;

interface PersistenceManagerInterface
{
    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity();

    /**
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getOmsOrderItemState(string $stateName): OmsOrderItemStateTransfer;
}
