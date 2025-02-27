<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Propel\Runtime\Collection\Collection;

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

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $expenseEntity
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function mapExpenseEntityToSalesExpenseTransfer(ExpenseTransfer $expenseTransfer, SpySalesExpense $expenseEntity): ExpenseTransfer
    {
        $expenseTransfer->fromArray($expenseEntity->toArray(), true);
        $expenseTransfer->setQuantity(1);
        $expenseTransfer->setSumGrossPrice($expenseEntity->getGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseEntity->getNetPrice());
        $expenseTransfer->setSumPrice($expenseEntity->getPrice());
        $expenseTransfer->setSumPriceToPayAggregation($expenseEntity->getPriceToPayAggregation());
        $expenseTransfer->setSumTaxAmount($expenseEntity->getTaxAmount());
        $expenseTransfer->setIsOrdered(true);

        $expenseTransfer = $this->mapExpenseUnitPrices($expenseTransfer);

        return $expenseTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesExpense> $salesExpenseEntities
     * @param list<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return list<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    public function mapSalesExpenseEntitiesToExpenseTransfers(
        Collection $salesExpenseEntities,
        array $expenseTransfers
    ): array {
        foreach ($salesExpenseEntities as $salesExpenseEntity) {
            $expenseTransfers[] = $this->mapExpenseEntityToSalesExpenseTransfer(new ExpenseTransfer(), $salesExpenseEntity);
        }

        return $expenseTransfers;
    }

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices or properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function mapExpenseUnitPrices(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->setUnitGrossPrice((int)round($expenseTransfer->getSumGrossPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitNetPrice((int)round($expenseTransfer->getSumNetPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitPrice((int)round($expenseTransfer->getSumPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitPriceToPayAggregation((int)round($expenseTransfer->getSumPriceToPayAggregation() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitTaxAmount((int)round($expenseTransfer->getSumTaxAmount() / $expenseTransfer->getQuantity()));

        return $expenseTransfer;
    }
}
