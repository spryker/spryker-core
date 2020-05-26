<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithMerchant(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        $merchantReference = $itemTransfer->getMerchantReference();

        if (!$merchantReference) {
            return $salesOrderItemEntityTransfer;
        }

        return $salesOrderItemEntityTransfer->setMerchantReference($merchantReference);
    }
}
