<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

class MerchantOrderDiscountFilter implements MerchantOrderDiscountFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function filterMerchantDiscounts(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        if (!$merchantOrderTransfer->getOrder()) {
            return $merchantOrderTransfer;
        }

        $merchantOrderItemIds = [];

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $merchantOrderItemIds[] = $merchantOrderItem->getIdOrderItem();
        }

        $orderTransfer = $merchantOrderTransfer->getOrder();

        $calculatedDiscountTransfers = array_filter(
            $orderTransfer->getCalculatedDiscounts()->getArrayCopy(),
            function (CalculatedDiscountTransfer $calculatedDiscountTransfer) use ($merchantOrderItemIds) {
                return $calculatedDiscountTransfer->getFkSalesOrderItem() === null || in_array($calculatedDiscountTransfer->getFkSalesOrderItem(), $merchantOrderItemIds);
            }
        );

        $orderTransfer->setCalculatedDiscounts(
            new ArrayObject($calculatedDiscountTransfers)
        );

        $merchantOrderTransfer->setOrder($orderTransfer);

        return $merchantOrderTransfer;
    }
}
