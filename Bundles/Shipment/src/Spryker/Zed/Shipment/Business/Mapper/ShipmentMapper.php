<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $sanitizedExpenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
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

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentEntityToShipmentMethodTransfer(
        SpySalesShipment $shipmentEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): ShipmentMethodTransfer {
        return $shipmentMethodTransfer->fromArray($shipmentEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransfer(
        SpySalesShipment $shipmentEntity,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        return $shipmentTransfer->fromArray($shipmentEntity->toArray(), true);
    }
}
