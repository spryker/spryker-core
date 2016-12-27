<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToTaxInterface;

class ExpenseTaxWithDiscountsCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToTaxInterface $taxFacade
     */
    public function __construct(DiscountCalculationToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->addExpenseTaxes($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addExpenseTaxes(QuoteTransfer $quoteTransfer)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }
            $itemUnitTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getUnitGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setUnitTaxAmountWithDiscounts($itemUnitTaxAmount);

            $itemSumTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getSumGrossPriceWithDiscounts(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setSumTaxAmountWithDiscounts($itemSumTaxAmount);

            $expenseTransfer->setUnitTaxTotal($expenseTransfer->getUnitTaxAmountWithDiscounts());
            $expenseTransfer->setSumTaxTotal($expenseTransfer->getSumTaxAmountWithDiscounts());

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
