<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;

class SalesExpenseMapper implements SalesExpenseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesExpenseEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function mapExpenseTransferToSalesExpenseEntity(ExpenseTransfer $expenseTransfer, SpySalesExpense $salesExpenseEntity): SpySalesExpense
    {
        $salesExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        return $salesExpenseEntity;
    }
}
