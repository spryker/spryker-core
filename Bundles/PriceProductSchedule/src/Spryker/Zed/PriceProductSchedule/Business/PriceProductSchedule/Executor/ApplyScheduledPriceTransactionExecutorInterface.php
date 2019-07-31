<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor;

interface ApplyScheduledPriceTransactionExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer[] $priceProductScheduleForEnable
     *
     * @return void
     */
    public function executeApplyScheduledPriceTransaction(array $priceProductScheduleForEnable): void;
}
