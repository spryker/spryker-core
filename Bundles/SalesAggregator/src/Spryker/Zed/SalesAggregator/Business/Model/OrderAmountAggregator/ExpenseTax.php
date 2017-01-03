<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;

class ExpenseTax implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface $taxFacade
     */
    public function __construct(SalesAggregatorToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->addTaxAmountToTaxableItems($orderTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $taxableItems
     *
     * @return void
     */
    protected function addTaxAmountToTaxableItems(\ArrayObject $taxableItems)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($taxableItems as $expenseTransfer) {
            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }
            $expenseTransfer->requireUnitGrossPrice()
                ->requireSumGrossPrice();

            $expenseUnitAmount = $this->calculateTaxAmount(
                $expenseTransfer->getUnitGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseSumTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getSumGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setUnitTaxAmount((int)round($expenseUnitAmount));
            $expenseTransfer->setSumTaxAmount((int)round($expenseSumTaxAmount));

            $expenseTransfer->setUnitTaxTotal((int)round($expenseUnitAmount));
            $expenseTransfer->setSumTaxTotal((int)round($expenseSumTaxAmount));
        }
    }

    /**
     * @param int $price
     * @param float $taxRate
     *
     * @return float
     */
    protected function calculateTaxAmount($price, $taxRate)
    {
        return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($price, $taxRate);
    }

}
