<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;

class OrderExpenseTaxWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var SalesAggregatorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param SalesAggregatorToTaxInterface $taxFacade
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
        $this->addExpenseTaxes($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addExpenseTaxes(OrderTransfer $orderTransfer)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }
            $itemUnitTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getUnitGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setUnitTaxAmountWithDiscounts((int)round($itemUnitTaxAmount));
            $expenseTransfer->setUnitTaxTotal((int)round($itemUnitTaxAmount));

            $itemSumTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getSumGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setSumTaxAmountWithDiscounts((int)round($itemSumTaxAmount));
            $expenseTransfer->setSumTaxTotal((int)round($itemSumTaxAmount));

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
