<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface CheckConditionForProcessAwareInterface
{
    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     * @param array|null $stateToTransitionsMap
     * @param array|null $orderItems
     *
     * @return int
     */
    public function checkConditionsForProcess(
        ProcessInterface $process,
        ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer,
        ?array $stateToTransitionsMap = null,
        ?array $orderItems = null
    ): int;
}
