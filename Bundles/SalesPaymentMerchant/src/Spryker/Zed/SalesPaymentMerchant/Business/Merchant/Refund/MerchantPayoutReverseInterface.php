<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund;

use Generated\Shared\Transfer\OrderTransfer;

interface MerchantPayoutReverseInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reversePayoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void;
}
