<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxInterface;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;
use Spryker\Shared\Calculation\CalculationTaxMode;

class TaxAmountAfterCancellationCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxInterface $taxFacade
     */
    public function __construct(CalculationToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateItemTaxAmountAfterCancellation($calculableObjectTransfer);
        $this->calculateOrderExpenseTaxAmountAfterCancellation($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function calculateItemTaxAmountAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {

            if (!$itemTransfer->getCanceledAmount()) {
                continue;
            }

            $canceledTaxableAmount = $itemTransfer->getUnitPriceToPayAggregation() - $itemTransfer->getCanceledAmount();

            $taxAmount = $this->calculateTaxAmount(
                $canceledTaxableAmount,
                $itemTransfer->getTaxRateAverageAggregation(),
                $calculableObjectTransfer->getTaxMode()
            );

            $itemTransfer->setTaxAmountAfterCancellation($itemTransfer->getUnitTaxAmountFullAggregation() - $taxAmount);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function calculateOrderExpenseTaxAmountAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {

            if (!$expenseTransfer->getCanceledAmount()) {
                continue;
            }

            $canceledTaxableAmount = $expenseTransfer->getUnitPriceToPayAggregation() - $expenseTransfer->getCanceledAmount();

            $taxAmount = $this->calculateTaxAmount(
                $canceledTaxableAmount,
                $expenseTransfer->getTaxRate(),
                $calculableObjectTransfer->getTaxMode()
            );

            $expenseTransfer->setTaxAmountAfterCancellation($expenseTransfer->getUnitTaxAmount() - $taxAmount);
        }
    }

    /**
     * @param int $price
     * @param float $taxRate
     * @param string $taxMode
     *
     * @return int
     */
    protected function calculateTaxAmount($price, $taxRate, $taxMode = CalculationTaxMode::TAX_MODE_GROSS)
    {
        if ($taxMode === CalculationTaxMode::TAX_MODE_NET) {
            return $this->taxFacade->getAccruedTaxAmountFromNetPrice($price, $taxRate);
        } else {
            return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($price, $taxRate);
        }
    }
}
