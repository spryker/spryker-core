<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDataExport;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesDataExportBusinessTester extends Actor
{
    use _generated\SalesDataExportBusinessTesterActions;

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function haveOrderExpense(int $idSalesOrder): void
    {
        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::FK_SALES_ORDER => $idSalesOrder,
        ]))->build();

        $salesExpenseEntity = SpySalesExpenseQuery::create()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOneOrCreate();

        $salesExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        $salesExpenseEntity->save();
    }

    /**
     * @param string $filePath
     *
     * @return void
     */
    public function removeGeneratedFile(string $filePath): void
    {
        unlink($filePath);
    }
}
