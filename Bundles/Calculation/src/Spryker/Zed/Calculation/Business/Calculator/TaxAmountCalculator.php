<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Calculation\CalculationConfig;
use Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxInterface;

class TaxAmountCalculator implements CalculatorInterface
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
        $taxMode = $calculableObjectTransfer->getTaxMode();

        $this->calculateTaxSumAmountForItems($calculableObjectTransfer->getItems(), $taxMode);
        $this->calculateTaxSumAmountForProductOptions($calculableObjectTransfer->getItems(), $taxMode);
        $this->calculateTaxSumAmountForExpenses($calculableObjectTransfer->getExpenses(), $taxMode);

        $this->calculateTaxUnitAmountForItems($calculableObjectTransfer->getItems(), $taxMode);
        $this->calculateTaxUnitAmountForProductOptions($calculableObjectTransfer->getItems(), $taxMode);
        $this->calculateTaxUnitAmountForExpenses($calculableObjectTransfer->getExpenses(), $taxMode);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxSumAmountForProductOptions(\ArrayObject $items, $taxMode)
    {
        foreach ($items as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->setSumTaxAmount(0);

                if (!$productOptionTransfer->getTaxRate()) {
                    continue;
                }

                $taxableAmount = $productOptionTransfer->getSumPrice() - $productOptionTransfer->getSumDiscountAmountAggregation();
                if ($taxableAmount <= 0) {
                    continue;
                }

                $sumTaxAmount = $this->calculateTaxAmount(
                    $taxableAmount,
                    $productOptionTransfer->getTaxRate(),
                    $taxMode
                );

                $productOptionTransfer->setSumTaxAmount($sumTaxAmount);
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxSumAmountForItems(\ArrayObject $items, $taxMode)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($items as $itemTransfer) {
            $itemTransfer->setSumTaxAmount(0);

            if (!$itemTransfer->getTaxRate()) {
                continue;
            }

            $taxableAmount = $itemTransfer->getSumPrice() - $itemTransfer->getSumDiscountAmountAggregation();
            if ($taxableAmount <= 0) {
                continue;
            }

            $sumTaxAmount = $this->calculateTaxAmount(
                $taxableAmount,
                $itemTransfer->getTaxRate(),
                $taxMode
            );

            $itemTransfer->setSumTaxAmount($sumTaxAmount);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxSumAmountForExpenses(ArrayObject $expenses, $taxMode)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setSumTaxAmount(0);

            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }

            $taxableAmount = $expenseTransfer->getSumPrice() - $expenseTransfer->getSumDiscountAmountAggregation();
            if ($taxableAmount <= 0) {
                continue;
            }

            $sumTaxAmount = $this->calculateTaxAmount(
                $taxableAmount,
                $expenseTransfer->getTaxRate(),
                $taxMode
            );

            $expenseTransfer->setSumTaxAmount($sumTaxAmount);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxUnitAmountForProductOptions(ArrayObject $items, $taxMode)
    {
        foreach ($items as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->setUnitTaxAmount(0);

                if (!$productOptionTransfer->getTaxRate()) {
                    continue;
                }

                $taxableAmount = $productOptionTransfer->getUnitPrice() - $productOptionTransfer->getUnitDiscountAmountAggregation();
                if ($taxableAmount <= 0) {
                    continue;
                }

                $sumTaxAmount = $this->calculateTaxAmount(
                    $taxableAmount,
                    $productOptionTransfer->getTaxRate(),
                    $taxMode
                );

                $productOptionTransfer->setUnitTaxAmount($sumTaxAmount);
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxUnitAmountForItems(\ArrayObject $items, $taxMode)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($items as $itemTransfer) {
            $itemTransfer->setUnitTaxAmount(0);

            if (!$itemTransfer->getTaxRate()) {
                continue;
            }

            $taxableAmount = $itemTransfer->getUnitPrice() - $itemTransfer->getUnitDiscountAmountAggregation();
            if ($taxableAmount <= 0) {
                continue;
            }

            $sumTaxAmount = $this->calculateTaxAmount(
                $taxableAmount,
                $itemTransfer->getTaxRate(),
                $taxMode
            );

            $itemTransfer->setUnitTaxAmount($sumTaxAmount);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     * @param string $taxMode
     *
     * @return void
     */
    protected function calculateTaxUnitAmountForExpenses(ArrayObject $expenses, $taxMode)
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();

        foreach ($expenses as $expenseTransfer) {
            $expenseTransfer->setUnitTaxAmount(0);

            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }

            $taxableAmount = $expenseTransfer->getUnitPrice() - $expenseTransfer->getUnitDiscountAmountAggregation();
            if ($taxableAmount <= 0) {
                continue;
            }

            $sumTaxAmount = $this->calculateTaxAmount(
                $taxableAmount,
                $expenseTransfer->getTaxRate(),
                $taxMode
            );

            $expenseTransfer->setUnitTaxAmount($sumTaxAmount);
        }
    }

    /**
     * @param int $price
     * @param float $taxRate
     * @param string $taxMode
     *
     * @return int
     */
    protected function calculateTaxAmount($price, $taxRate, $taxMode = CalculationConfig::TAX_MODE_GROSS)
    {
        if ($taxMode === CalculationConfig::TAX_MODE_NET) {
            return $this->taxFacade->getAccruedTaxAmountFromNetPrice($price, $taxRate);
        } else {
            return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($price, $taxRate);
        }
    }

}
