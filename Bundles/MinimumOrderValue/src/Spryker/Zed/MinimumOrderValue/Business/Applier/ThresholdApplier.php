<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Applier;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig;

class ThresholdApplier implements ThresholdApplierInterface
{
    protected const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface
     */
    protected $minimumOrderValueDataSourceStrategy;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig $config
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy,
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueConfig $config,
        MinimumOrderValueToMessengerFacadeInterface $messengerFacade
    ) {
        $this->minimumOrderValueDataSourceStrategy = $minimumOrderValueDataSourceStrategy;
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
        $this->config = $config;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function applyOnQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $minimumOrderValueThresholdTransfers = $this->minimumOrderValueDataSourceStrategy->findApplicableThresholds($quoteTransfer);

        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $minimumOrderValueThresholdTransfers,
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        if (empty($minimumOrderValueThresholdTransfers)) {
            return $quoteTransfer;
        }

        $this->removeMinimumOrderValueExpensesFromQuote($quoteTransfer);

        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $this->addExpenseToQuote($quoteTransfer, $minimumOrderValueThresholdTransfer);
            $this->addInfoMessageToMessenger($minimumOrderValueThresholdTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function applicableForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $minimumOrderValueThresholdTransfers = $this->minimumOrderValueDataSourceStrategy->findApplicableThresholds($quoteTransfer);

        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $minimumOrderValueThresholdTransfers,
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        if (empty($minimumOrderValueThresholdTransfers)) {
            return true;
        }

        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $this->addErrorMessageToCheckoutResponse($checkoutResponseTransfer, $minimumOrderValueThresholdTransfer);
        }

        return $checkoutResponseTransfer->getErrors()->count() === 0;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function removeMinimumOrderValueExpensesFromQuote(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getExpenses() as $expenseOffset => $expenseTransfer) {
            if ($expenseTransfer->getType() === static::THRESHOLD_EXPENSE_TYPE) {
                $quoteTransfer->getExpenses()->offsetUnset($expenseOffset);
                continue;
            }
        }
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
            ->setType(static::THRESHOLD_EXPENSE_TYPE)
            ->setQuantity(1);

        if ($priceMode === $this->config->getNetPriceMode()) {
            $expenseTransfer->setUnitGrossPrice(0);
            $expenseTransfer->setSumGrossPrice(0);
            $expenseTransfer->setUnitNetPrice($expensePrice);

            return $expenseTransfer;
        }

        $expenseTransfer->setUnitNetPrice(0);
        $expenseTransfer->setSumNetPrice(0);
        $expenseTransfer->setUnitGrossPrice($expensePrice);

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function addErrorMessageToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
    ): void {
        $this->assertRequiredAttributes($minimumOrderValueThresholdTransfer);
        $minimumOrderValueThresholdTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey());

        if (!$minimumOrderValueThresholdTransferStrategy->isApplicable($minimumOrderValueThresholdTransfer)) {
            return;
        }

        $checkoutResponseTransfer->addError(
            (new CheckoutErrorTransfer())
                ->setMessage($minimumOrderValueThresholdTransfer->getMessageGlossaryKey())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function addInfoMessageToMessenger(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): void
    {
        $this->messengerFacade->addInfoMessage(
            $this->createMessageTransfer($minimumOrderValueThresholdTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($minimumOrderValueThresholdTransfer->getMessageGlossaryKey());
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
}
