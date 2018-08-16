<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();

        if (!$productMeasurementSalesUnitTransfer) {
            return $salesOrderItemEntity;
        }

        $productMeasurementSalesUnitTransfer->requireProductMeasurementUnit();
        $quantityMeasurementUnitTransfer = $productMeasurementSalesUnitTransfer->getProductMeasurementUnit();

        $quantityBaseMeasurementUnitName = $productMeasurementSalesUnitTransfer
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $salesOrderItemEntity->setQuantityBaseMeasurementUnitName($quantityBaseMeasurementUnitName);
        $salesOrderItemEntity->setQuantityMeasurementUnitName($quantityMeasurementUnitTransfer->getName());
        $salesOrderItemEntity->setQuantityMeasurementUnitCode($quantityMeasurementUnitTransfer->getCode());

        $salesOrderItemEntity->setQuantityMeasurementUnitPrecision($productMeasurementSalesUnitTransfer->getPrecision());
        $salesOrderItemEntity->setQuantityMeasurementUnitConversion($productMeasurementSalesUnitTransfer->getConversion());

        return $salesOrderItemEntity;
    }
}
