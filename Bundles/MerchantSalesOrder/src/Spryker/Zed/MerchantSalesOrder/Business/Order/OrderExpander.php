<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithMerchants(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (
                !$itemTransfer->getMerchantReference()
                || in_array($itemTransfer->getMerchantReference(), $orderTransfer->getMerchantReferences())
            ) {
                continue;
            }

            $orderTransfer->addMerchantReference($itemTransfer->getMerchantReference());
        }

        return $orderTransfer;
    }
}
