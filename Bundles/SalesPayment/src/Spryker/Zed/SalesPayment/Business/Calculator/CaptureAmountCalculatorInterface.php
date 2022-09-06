<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Calculator;

use Generated\Shared\Transfer\OrderTransfer;

interface CaptureAmountCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return int
     */
    public function getCaptureAmount(OrderTransfer $orderTransfer, array $orderItemIds): int;
}
