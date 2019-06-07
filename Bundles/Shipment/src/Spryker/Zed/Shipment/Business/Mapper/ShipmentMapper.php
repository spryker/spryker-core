<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $sanitizedExpenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return void
     */
    public function mapOrderSalesExpenseEntityToExpenseTransfer(
        ExpenseTransfer $sanitizedExpenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ): SpySalesExpense {
        $salesOrderExpenseEntity->fromArray($sanitizedExpenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($sanitizedExpenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($sanitizedExpenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($sanitizedExpenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($sanitizedExpenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($sanitizedExpenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($sanitizedExpenseTransfer->getSumPriceToPayAggregation());

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentMethodTransferToShipmentEntity(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpySalesShipment $shipmentEntity
    ): SpySalesShipment {
        $shipmentEntity->fromArray($shipmentMethodTransfer->modifiedToArray());

        return $shipmentEntity;
    }
}
