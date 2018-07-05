<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class AmountSalesUnitOrderItemExpander implements AmountSalesUnitOrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithAmountSalesUnit(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntity): SpySalesOrderItemEntityTransfer
    {
        if (!$itemTransfer->getAmountSalesUnit()) {
            return $salesOrderItemEntity;
        }

        $amountBaseMeasurementUnitName = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $amountMeasurementUnitName = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $salesOrderItemEntity->setAmountBaseMeasurementUnitName($amountBaseMeasurementUnitName);
        $salesOrderItemEntity->setAmountMeasurementUnitName($amountMeasurementUnitName);

        $salesOrderItemEntity->setAmountMeasurementUnitPrecision($itemTransfer->getAmountSalesUnit()->getPrecision());
        $salesOrderItemEntity->setAmountMeasurementUnitConversion($itemTransfer->getAmountSalesUnit()->getConversion());

        return $salesOrderItemEntity;
    }
}
