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
use Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;

class ExpenseCalculator implements ExpenseCalculatorInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface
     */
    protected $minimumOrderValueDataSourceStrategyResolver;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver
     */
    public function __construct(
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver
    ) {
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
        $this->minimumOrderValueDataSourceStrategyResolver = $minimumOrderValueDataSourceStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function addMinimumOrderValueExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $this->minimumOrderValueDataSourceStrategyResolver->findApplicableThresholds($calculableObjectTransfer->getOriginalQuote()),
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

        $this->addMinimumOrderValueExpense($minimumOrderValueThresholdTransfer, $calculableObjectTransfer, $calculatedFee);
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
            ->requireValue()
            ->requireThreshold();

        $minimumOrderValueThresholdTransfer->getMinimumOrderValueType()
            ->requireKey();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $fee
     *
     * @return void
     */
    protected function addMinimumOrderValueExpense(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer,
        CalculableObjectTransfer $calculableObjectTransfer,
        int $fee
    ): void {
        $calculableObjectTransfer->addExpense(
            $this->createExpenseByPriceMode($minimumOrderValueThresholdTransfer, $fee, $calculableObjectTransfer->getPriceMode())
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
