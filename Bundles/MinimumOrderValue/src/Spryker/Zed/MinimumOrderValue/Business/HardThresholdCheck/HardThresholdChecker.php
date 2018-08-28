<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\HardThresholdCheck;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface;

class HardThresholdChecker implements HardThresholdCheckerInterface
{
    protected const THRESHOLD_GLOSSARY_PARAMETER = '{{threshold}}';
    protected const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface
     */
    protected $minimumOrderValueDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver,
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueToMessengerFacadeInterface $messengerFacade,
        MinimumOrderValueToMoneyFacadeInterface $moneyFacade
    ) {
        $this->minimumOrderValueDataSourceStrategyResolver = $minimumOrderValueDataSourceStrategyResolver;
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
        $this->messengerFacade = $messengerFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkQuoteForHardThreshold(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $minimumOrderValueThresholdTransfers = $this->minimumOrderValueDataSourceStrategyResolver->findApplicableThresholds($quoteTransfer);

        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $minimumOrderValueThresholdTransfers,
            MinimumOrderValueConfig::GROUP_HARD
        );

        if (empty($minimumOrderValueThresholdTransfers)) {
            return true;
        }

        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $this->addErrorMessageToCheckoutResponse($checkoutResponseTransfer, $quoteTransfer->getCurrency(), $minimumOrderValueThresholdTransfer);
        }

        return $checkoutResponseTransfer->getErrors()->count() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return void
     */
    protected function addErrorMessageToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        CurrencyTransfer $currencyTransfer,
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
                ->setParameters([
                    static::THRESHOLD_GLOSSARY_PARAMETER => $this->moneyFacade->formatWithSymbol(
                        $this->createMoneyTransfer($minimumOrderValueThresholdTransfer, $currencyTransfer)
                    ),
                ])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createMoneyTransfer(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer,
        CurrencyTransfer $currencyTransfer
    ): MoneyTransfer {
        return (new MoneyTransfer())
            ->setAmount(
                (string)$minimumOrderValueThresholdTransfer->getThreshold()
            )->setCurrency($currencyTransfer);
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
