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
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
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
        $minimumOrderValueTransfers = $this->minimumOrderValueDataSourceStrategy->findApplicableThresholds($quoteTransfer);

        $minimumOrderValueTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $minimumOrderValueTransfers,
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        if (empty($minimumOrderValueTransfers)) {
            return $quoteTransfer;
        }

        $this->removeMinimumOrderValueExpensesFromQuote($quoteTransfer);

        foreach ($minimumOrderValueTransfers as $minimumOrderValueTransfer) {
            $this->addExpenseToQuote($quoteTransfer, $minimumOrderValueTransfer);
            $this->addInfoMessageToMessenger($quoteTransfer, $minimumOrderValueTransfer);
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
        $minimumOrderValueTransfers = $this->minimumOrderValueDataSourceStrategy->findApplicableThresholds($quoteTransfer);

        $minimumOrderValueTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $minimumOrderValueTransfers,
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        if (empty($minimumOrderValueTransfers)) {
            return true;
        }

        foreach ($minimumOrderValueTransfers as $minimumOrderValueTransfer) {
            $this->addErrorMessageToCheckoutResponse($quoteTransfer, $checkoutResponseTransfer, $minimumOrderValueTransfer);
        }

        return $checkoutResponseTransfer->getErrors()->count() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function addExpenseToQuote(QuoteTransfer $quoteTransfer, MinimumOrderValueTransfer $minimumOrderValueTransfer): void
    {
        $this->assertRequiredAttributes($minimumOrderValueTransfer);
        $minimumOrderValueTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTransfer->getMinimumOrderValueType()->getKey());

        if (!$minimumOrderValueTransferStrategy->isApplicable($minimumOrderValueTransfer)) {
            return;
        }

        $calculatedFees = $minimumOrderValueTransferStrategy->calculateFee($minimumOrderValueTransfer);

        if (!$calculatedFees) {
            return;
        }

        $this->addMinimumOrderValueExpenseToQuote($minimumOrderValueTransfer, $quoteTransfer, $calculatedFees);
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $calculatedFees
     *
     * @return void
     */
    protected function addMinimumOrderValueExpenseToQuote(
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        QuoteTransfer $quoteTransfer,
        int $calculatedFees
    ): void {
        $quoteTransfer->addExpense(
            $this->createExpenseByPriceMode($minimumOrderValueTransfer, $calculatedFees, $quoteTransfer->getPriceMode())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param int $expensePrice
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseByPriceMode(
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        int $expensePrice,
        string $priceMode
    ): ExpenseTransfer {
        $expenseTransfer = (new ExpenseTransfer())
            ->setName($minimumOrderValueTransfer->getMinimumOrderValueType()->getKey())
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function addErrorMessageToCheckoutResponse(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): void {
        $this->assertRequiredAttributes($minimumOrderValueTransfer);
        $minimumOrderValueTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTransfer->getMinimumOrderValueType()->getKey());

        if (!$minimumOrderValueTransferStrategy->isApplicable($minimumOrderValueTransfer)) {
            return;
        }

        if ($quoteTransfer->getCustomer() === null || $quoteTransfer->getCustomer()->getLocale() === null) {
            return;
        }

        foreach ($minimumOrderValueTransfer->getLocalizedMessages() as $localizedMessageTransfer) {
            if ($localizedMessageTransfer->getLocaleCode() === $quoteTransfer->getCustomer()->getLocale()->getLocaleName()) {
                $checkoutResponseTransfer->addError(
                    (new CheckoutErrorTransfer())
                        ->setMessage($localizedMessageTransfer->getMessage())
                );

                return;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function addInfoMessageToMessenger(QuoteTransfer $quoteTransfer, MinimumOrderValueTransfer $minimumOrderValueTransfer): void
    {
        if ($quoteTransfer->getCustomer() === null || $quoteTransfer->getCustomer()->getLocale() === null) {
            return;
        }

        foreach ($minimumOrderValueTransfer->getLocalizedMessages() as $localizedMessageTransfer) {
            if ($localizedMessageTransfer->getLocaleCode() === $quoteTransfer->getCustomer()->getLocale()->getLocaleName()) {
                $this->messengerFacade->addInfoMessage($this->createMessageTransfer($localizedMessageTransfer));

                return;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer $localizedMessageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(MinimumOrderValueLocalizedMessageTransfer $localizedMessageTransfer): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($localizedMessageTransfer->getMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(MinimumOrderValueTransfer $minimumOrderValueTransfer): void
    {
        $minimumOrderValueTransfer
            ->requireMinimumOrderValueType()
            ->requireSubTotal()
            ->requireValue();

        $minimumOrderValueTransfer->getMinimumOrderValueType()
            ->requireKey();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer[] $minimumOrderValueTransfers
     * @param string $thresholdGroup
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    protected function filterMinimumOrderValuesByThresholdGroup(array $minimumOrderValueTransfers, string $thresholdGroup): array
    {
        return array_filter($minimumOrderValueTransfers, function (MinimumOrderValueTransfer $minimumOrderValueTransfers) use ($thresholdGroup) {
            return $minimumOrderValueTransfers->getMinimumOrderValueType()->getThresholdGroup() === $thresholdGroup;
        });
    }
}
