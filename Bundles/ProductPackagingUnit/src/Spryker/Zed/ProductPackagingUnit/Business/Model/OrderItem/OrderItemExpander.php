<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

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
    public function expandSalesOrderItemWithAmountSalesUnit(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntity): SpySalesOrderItemEntityTransfer
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

        $amountMeasurementUnitCode = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementUnit()
            ->getCode();

        $salesOrderItemEntity->setAmountBaseMeasurementUnitName($amountBaseMeasurementUnitName);
        $salesOrderItemEntity->setAmountMeasurementUnitName($amountMeasurementUnitName);
        $salesOrderItemEntity->setAmountMeasurementUnitCode($amountMeasurementUnitCode);

        $salesOrderItemEntity->setAmountMeasurementUnitPrecision($itemTransfer->getAmountSalesUnit()->getPrecision());
        $salesOrderItemEntity->setAmountMeasurementUnitConversion($itemTransfer->getAmountSalesUnit()->getConversion());

        return $salesOrderItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItemWithAmountAndAmountSku(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntity): SpySalesOrderItemEntityTransfer
    {
        if (!$itemTransfer->getAmountLeadProduct()) {
            return $salesOrderItemEntity;
        }

        $packagingUnitLeadProductSku = $itemTransfer->getAmountLeadProduct()->getSku();
        $packagingUnitAmount = $itemTransfer->getAmount();

        $salesOrderItemEntity->setAmount($packagingUnitAmount);
        $salesOrderItemEntity->setAmountSku($packagingUnitLeadProductSku);

        return $salesOrderItemEntity;
    }
}
