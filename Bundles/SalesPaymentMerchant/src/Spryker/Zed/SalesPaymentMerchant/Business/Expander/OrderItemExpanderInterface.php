<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderItemExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrderItemTransfer> $orderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrderItemTransfer>
     */
    public function expandOrderItemsWithTransferId(
        array $orderItemTransfers,
        OrderTransfer $orderTransfer
    ): array;
}
