<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithMerchantReferences(OrderTransfer $orderTransfer): OrderTransfer
    {
        $merchantReferences = $this->getMerchantReferences($orderTransfer);

        return $orderTransfer->setMerchantReferences($merchantReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function getMerchantReferences(OrderTransfer $orderTransfer): array
    {
        $merchantReferences = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (
                !$itemTransfer->getMerchantReference()
                || isset($merchantReferences[$itemTransfer->getMerchantReference()])
            ) {
                continue;
            }

            $merchantReferences[$itemTransfer->getMerchantReference()] = $itemTransfer->getMerchantReference();
        }

        return array_values($merchantReferences);
    }
}
