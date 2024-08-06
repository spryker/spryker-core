<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface PaymentMethodPayoutCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool;
}
