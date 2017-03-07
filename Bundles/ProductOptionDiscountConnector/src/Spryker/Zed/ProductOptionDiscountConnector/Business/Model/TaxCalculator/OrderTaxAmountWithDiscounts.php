<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface;

class OrderTaxAmountWithDiscounts implements OrderAmountAggregatorInterface, CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $totalTaxAmount = $this->sumItemTax($orderTransfer->getItems());
        $totalTaxAmount += $this->sumExpenseTax($orderTransfer->getExpenses());

        $taxTotalsTransfer = $this->getTaxTotals($totalTaxAmount);
        $orderTransfer->getTotals()->setTaxTotal($taxTotalsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = $this->sumItemTax($quoteTransfer->getItems());
        $totalTaxAmount += $this->sumExpenseTax($quoteTransfer->getExpenses());

        $taxTotalsTransfer = $this->getTaxTotals($totalTaxAmount);
        $quoteTransfer->getTotals()->setTaxTotal($taxTotalsTransfer);
    }

    /**
     * @param int $totalTaxAmount
     *
     * @return \Generated\Shared\Transfer\TaxTotalTransfer
     */
    protected function getTaxTotals($totalTaxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount((int)round($totalTaxAmount));

        return $taxTotalTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function sumExpenseTax(ArrayObject $expenses)
    {
        $totalTaxAmount = 0;
        foreach ($expenses as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     *
     * @return int
     */
    protected function sumItemTax(ArrayObject $items)
    {
        $totalTaxAmount = 0;
        foreach ($items as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmountWithProductOptionAndDiscountAmounts();
        }

        return $totalTaxAmount;
    }

}
