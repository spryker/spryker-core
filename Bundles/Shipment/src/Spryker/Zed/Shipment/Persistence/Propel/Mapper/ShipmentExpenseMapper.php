<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentExpenseMapper implements ShipmentExpenseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function mapExpenseTransferToOrderSalesExpenseEntity(
        ExpenseTransfer $expenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ): SpySalesExpense {
        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function mapOrderSalesExpenseEntityToExpenseTransfer(
        SpySalesExpense $salesOrderExpenseEntity,
        ExpenseTransfer $expenseTransfer
    ): ExpenseTransfer {
        $expenseTransfer->fromArray($salesOrderExpenseEntity->toArray(), true);
        $expenseTransfer->setSumGrossPrice($salesOrderExpenseEntity->getGrossPrice());
        $expenseTransfer->setSumNetPrice($salesOrderExpenseEntity->getNetPrice());
        $expenseTransfer->setSumPrice($salesOrderExpenseEntity->getPrice());
        $expenseTransfer->setSumTaxAmount($salesOrderExpenseEntity->getTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation($salesOrderExpenseEntity->getDiscountAmountAggregation());
        $expenseTransfer->setSumPriceToPayAggregation($salesOrderExpenseEntity->getPriceToPayAggregation());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapExpenseTransferToShipmentEntity(
        ExpenseTransfer $expenseTransfer,
        SpySalesShipment $salesShipmentEntity
    ): SpySalesShipment {
        $idSalesExpense = $expenseTransfer->getIdSalesExpense();
        if ($idSalesExpense !== null) {
            $salesShipmentEntity->setFkSalesExpense($idSalesExpense);
        }

        return $salesShipmentEntity;
    }
}
