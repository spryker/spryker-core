<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\ExpenseCalculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Business\QuoteExpander\QuoteExpanderInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;

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
     * @param \Spryker\Zed\MinimumOrderValue\Business\QuoteExpander\QuoteExpanderInterface $quoteExpander
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     */
    public function __construct(
        QuoteExpanderInterface $quoteExpander,
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
    ) {
        $this->quoteExpander = $quoteExpander;
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function addMinimumOrderValueExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $quoteTransfer = $this->quoteExpander->addMinimumOrderValueThresholdsToQuote(
            $calculableObjectTransfer->getOriginalQuote()
        );

        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $quoteTransfer->getMinimumOrderValueThresholdCollection()->getArrayCopy(),
            MinimumOrderValueConfig::GROUP_SOFT
        );

        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $this->addExpense($calculableObjectTransfer, $minimumOrderValueThresholdTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function addExpense(CalculableObjectTransfer $calculableObjectTransfer, MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): void
    {
        $this->assertRequiredAttributes($minimumOrderValueThresholdTransfer);
        $minimumOrderValueThresholdTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey());

        if (!$minimumOrderValueThresholdTransferStrategy->isApplicable($minimumOrderValueThresholdTransfer)) {
            return;
        }

        $calculatedFee = $minimumOrderValueThresholdTransferStrategy->calculateFee($minimumOrderValueThresholdTransfer);

        if (!$calculatedFee) {
            return;
        }

        $this->addMinimumOrderValueExpenseToCalculableObject($minimumOrderValueThresholdTransfer, $calculableObjectTransfer, $calculatedFee);
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
            ->requireComparedToSubtotal()
            ->requireThreshold();

        $minimumOrderValueThresholdTransfer->getMinimumOrderValueType()
            ->requireKey();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $calculatedFees
     *
     * @return void
     */
    protected function addMinimumOrderValueExpenseToCalculableObject(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer,
        CalculableObjectTransfer $calculableObjectTransfer,
        int $calculatedFees
    ): void {
        $calculableObjectTransfer->addExpense(
            $this->createExpenseByPriceMode($minimumOrderValueThresholdTransfer, $calculatedFees, $calculableObjectTransfer->getPriceMode())
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
            ->setTaxRate(5) //TODO: MOST IMPORTANT PART..PAGE TO SET TAX SET FOR MOV AND QUERY TO RETRIEVE + SET TAX RATE
            ->setQuantity(1);

        if ($priceMode === MinimumOrderValueConfig::PRICE_MODE_NET) {
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
