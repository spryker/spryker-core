<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Business\OrderItem;

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
    public function expandOrderItemWithReference(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        $salesOrderItemEntityTransfer->setOrderItemReference(
            $this->generateOrderItemReference($salesOrderItemEntityTransfer)
        );

        return $salesOrderItemEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return string
     */
    protected function generateOrderItemReference(SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): string
    {
        return md5(uniqid($this->getUniqueKeyFromEntity($salesOrderItemEntityTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return string
     */
    protected function getUniqueKeyFromEntity(SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): string
    {
        return implode(
            '-',
            array_filter(
                $salesOrderItemEntityTransfer->toArray(false),
                function ($elements) {
                    return is_int($elements) || is_string($elements);
                }
            )
        );
    }
}
