<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface;

class QuantitySalesUnitOrderItemExpanderPreSavePlugin implements OrderItemExpanderPreSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntity): SpySalesOrderItemEntityTransfer
    {
        if (!$itemTransfer->getQuantitySalesUnit()) {
            return $salesOrderItemEntity;
        }

        $salesOrderItemEntity->setQuantityBaseMeasurementUnitName(
            $itemTransfer->getQuantitySalesUnit()
                ->getProductMeasurementBaseUnit()
                ->getProductMeasurementUnit()
                ->getName()
        );

        $salesOrderItemEntity->setQuantityMeasurementUnitName($itemTransfer->getQuantitySalesUnit()->getProductMeasurementUnit()->getName());
        $salesOrderItemEntity->setQuantityMeasurementUnitPrecision($itemTransfer->getQuantitySalesUnit()->getPrecision());
        $salesOrderItemEntity->setQuantityMeasurementUnitConversion($itemTransfer->getQuantitySalesUnit()->getConversion());

        return $salesOrderItemEntity;
    }
}
