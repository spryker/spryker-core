<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\ExpenseCalculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;

class ExpenseCalculator implements ExpenseCalculatorInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\QuoteExpander\QuoteExpanderInterface
     */
    protected $quoteExpander;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig
     */
    protected $config;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeMinimumOrderValueExpensesFromQuote(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $quoteTransfer = $calculableObjectTransfer->getOriginalQuote();
        foreach ($quoteTransfer->getExpenses() as $expenseOffset => $expenseTransfer) {
            if ($expenseTransfer->getType() === MinimumOrderValueConfig::THRESHOLD_EXPENSE_TYPE) {
                $quoteTransfer->getExpenses()->offsetUnset($expenseOffset);
                continue;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function addMinimumOrderValueExpensesToQuote(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $quoteTransfer = $calculableObjectTransfer->getOriginalQuote();

        if (!$quoteTransfer->getMinimumOrderValueThresholds() || !$quoteTransfer->getMinimumOrderValueThresholds()->count()) {
            $quoteTransfer = $this->quoteExpander->addMinimumOrderValueThresholdsToQuote($quoteTransfer);
        }

        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $quoteTransfer->getMinimumOrderValueThresholds()->getArrayCopy(),
            MinimumOrderValueConfig::GROUP_SOFT
        );

        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $this->addExpenseToQuote($quoteTransfer, $minimumOrderValueThresholdTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function addExpenseToQuote(QuoteTransfer $quoteTransfer, MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): void
    {
        $this->assertRequiredAttributes($minimumOrderValueThresholdTransfer);
        $minimumOrderValueThresholdTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey());

        if (!$minimumOrderValueThresholdTransferStrategy->isApplicable($minimumOrderValueThresholdTransfer)) {
            return;
        }

        $calculatedFees = $minimumOrderValueThresholdTransferStrategy->calculateFee($minimumOrderValueThresholdTransfer);

        if (!$calculatedFees) {
            return;
        }

        $this->addMinimumOrderValueExpenseToQuote($minimumOrderValueThresholdTransfer, $quoteTransfer, $calculatedFees);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[] $minimumOrderValueThresholdTransfers
     * @param string $thresholdGroup
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    protected function filterMinimumOrderValuesByThresholdGroup(array $minimumOrderValueThresholdTransfers, string $thresholdGroup): array
    {
        return array_filter($minimumOrderValueThresholdTransfers, function (MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfers) use ($thresholdGroup) {
            return $minimumOrderValueThresholdTransfers->getMinimumOrderValueType()->getThresholdGroup() === $thresholdGroup;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): void
    {
        $minimumOrderValueThresholdTransfer
            ->requireMinimumOrderValueType()
            ->requireSubTotal()
            ->requireValue();

        $minimumOrderValueThresholdTransfer->getMinimumOrderValueType()
            ->requireKey();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $calculatedFees
     *
     * @return void
     */
    protected function addMinimumOrderValueExpenseToQuote(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer,
        QuoteTransfer $quoteTransfer,
        int $calculatedFees
    ): void {
        $quoteTransfer->addExpense(
            $this->createExpenseByPriceMode($minimumOrderValueThresholdTransfer, $calculatedFees, $quoteTransfer->getPriceMode())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param int $expensePrice
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseByPriceMode(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer,
        int $expensePrice,
        string $priceMode
    ): ExpenseTransfer {
        $expenseTransfer = (new ExpenseTransfer())
            ->setName($minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey())
            ->setType(MinimumOrderValueConfig::THRESHOLD_EXPENSE_TYPE)
            ->setUnitPrice($expensePrice)
            ->setSumPrice($expensePrice)
            ->setUnitPriceToPayAggregation($expensePrice)
            ->setSumPriceToPayAggregation($expensePrice)
            ->setTaxRate(5) //MOST IMPORTANT PART..PAGE TO SET TAX SET FOR MOV AND QUERY TO RETRIEVE + SET TAX RATE
            ->setQuantity(1);

        if ($priceMode === $this->config->getNetPriceMode()) {
            $expenseTransfer->setUnitGrossPrice(0);
            $expenseTransfer->setSumGrossPrice(0);
            $expenseTransfer->setUnitNetPrice($expensePrice);
            $expenseTransfer->setSumNetPrice($expensePrice);

            return $expenseTransfer;
        }

        $expenseTransfer->setUnitNetPrice(0);
        $expenseTransfer->setSumNetPrice(0);
        $expenseTransfer->setUnitGrossPrice($expensePrice);
        $expenseTransfer->setSumGrossPrice($expensePrice);

        return $expenseTransfer;
    }
}
