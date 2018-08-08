<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Applier;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;

class ThresholdApplier implements ThresholdApplierInterface
{
    /**
     * @uses CalculationPriceMode::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    protected const THRESHOLD_EXPENSE_NAME = 'minimum-order-value.expense.name';
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
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy,
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueToMessengerFacadeInterface $messengerFacade
    ) {
        $this->minimumOrderValueDataSourceStrategy = $minimumOrderValueDataSourceStrategy;
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
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
    public function applyOnCheckoutResponse(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
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
            $this->addMessageToCheckoutResponse($quoteTransfer, $checkoutResponseTransfer, $minimumOrderValueTransfer);
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
        $minimumOrderValueTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTransfer->getMinimumOrderValueType()->getKey());

        $minimumOrderValueTransfer->requireSubTotal();
        if (!$minimumOrderValueTransferStrategy->isApplicable($minimumOrderValueTransfer)) {
            return;
        }

        $calculatedFees = $minimumOrderValueTransferStrategy->calculateFee($minimumOrderValueTransfer);

        if (!$calculatedFees) {
            return;
        }

        $this->addMinimumOrderValueExpenseToQuote($quoteTransfer, $calculatedFees);
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $calculatedFees
     *
     * @return void
     */
    protected function addMinimumOrderValueExpenseToQuote(QuoteTransfer $quoteTransfer, int $calculatedFees): void
    {
        $expenseTransfer = (new ExpenseTransfer())
            ->setName(static::THRESHOLD_EXPENSE_NAME)
            ->setType(static::THRESHOLD_EXPENSE_TYPE)
            ->setQuantity(1);

        $quoteTransfer->addExpense(
            $this->setExpensePrice(
                $expenseTransfer,
                $calculatedFees,
                $quoteTransfer->getPriceMode()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function setExpensePrice(ExpenseTransfer $expenseTransfer, int $price, string $priceMode): ExpenseTransfer
    {
        if ($priceMode === static::PRICE_MODE_NET) {
            $expenseTransfer->setUnitGrossPrice(0);
            $expenseTransfer->setSumGrossPrice(0);
            $expenseTransfer->setUnitNetPrice($price);

            return $expenseTransfer;
        }

        $expenseTransfer->setUnitNetPrice(0);
        $expenseTransfer->setSumNetPrice(0);
        $expenseTransfer->setUnitGrossPrice($price);

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function addMessageToCheckoutResponse(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): void {
        $minimumOrderValueTransferStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy($minimumOrderValueTransfer->getMinimumOrderValueType()->getKey());

        $minimumOrderValueTransfer->requireSubTotal();
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
                $this->messengerFacade->addInfoMessage($localizedMessageTransfer->getMessage());

                return;
            }
        }
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
