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
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function mapExpenseTransferToSalesExpenseEntity(ExpenseTransfer $expenseTransfer): SpySalesExpense
    {
        $salesOrderExpenseEntity = new SpySalesExpense();

        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        return $salesOrderExpenseEntity;
    }
}
