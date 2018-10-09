<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\HardThresholdCheck;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface;

class HardThresholdChecker implements HardThresholdCheckerInterface
{
    protected const THRESHOLD_GLOSSARY_PARAMETER = '{{threshold}}';
    protected const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';
    protected const CHECKOUT_ERROR_REDIRECT = '/checkout/summary';

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface
     */
    protected $salesOrderThresholdDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver,
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver,
        SalesOrderThresholdToMessengerFacadeInterface $messengerFacade,
        SalesOrderThresholdToMoneyFacadeInterface $moneyFacade
    ) {
        $this->salesOrderThresholdDataSourceStrategyResolver = $salesOrderThresholdDataSourceStrategyResolver;
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
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
        $salesOrderThresholdValueTransfers = $this->salesOrderThresholdDataSourceStrategyResolver->findApplicableThresholds($quoteTransfer);

        $salesOrderThresholdValueTransfers = $this->filterSalesOrderThresholdsByThresholdGroup(
            $salesOrderThresholdValueTransfers,
            SalesOrderThresholdConfig::GROUP_HARD
        );

        if (empty($salesOrderThresholdValueTransfers)) {
            return true;
        }

        foreach ($salesOrderThresholdValueTransfers as $salesOrderThresholdValueTransfer) {
            $this->addErrorMessageToCheckoutResponse($checkoutResponseTransfer, $quoteTransfer->getCurrency(), $salesOrderThresholdValueTransfer);
        }

        if ($checkoutResponseTransfer->getErrors()->count() > 0) {
            $checkoutResponseTransfer->setIsSuccess(false)
                ->setIsExternalRedirect(true)
                ->setRedirectUrl(static::CHECKOUT_ERROR_REDIRECT);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return void
     */
    protected function addErrorMessageToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        CurrencyTransfer $currencyTransfer,
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
    ): void {
        $this->assertRequiredAttributes($salesOrderThresholdValueTransfer);
        $salesOrderThresholdValueTransferStrategy = $this->salesOrderThresholdStrategyResolver
            ->resolveSalesOrderThresholdStrategy($salesOrderThresholdValueTransfer->getSalesOrderThresholdType()->getKey());

        if (!$salesOrderThresholdValueTransferStrategy->isApplicable($salesOrderThresholdValueTransfer)) {
            return;
        }

        $checkoutResponseTransfer->addError(
            (new CheckoutErrorTransfer())
                ->setMessage($salesOrderThresholdValueTransfer->getMessageGlossaryKey())
                ->setParameters([
                    static::THRESHOLD_GLOSSARY_PARAMETER => $this->moneyFacade->formatWithSymbol(
                        $this->createMoneyTransfer($salesOrderThresholdValueTransfer, $currencyTransfer)
                    ),
                ])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createMoneyTransfer(
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer,
        CurrencyTransfer $currencyTransfer
    ): MoneyTransfer {
        return (new MoneyTransfer())
            ->setAmount(
                (string)$salesOrderThresholdValueTransfer->getThreshold()
            )->setCurrency($currencyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): void
    {
        $salesOrderThresholdValueTransfer
            ->requireSalesOrderThresholdType()
            ->requireValue()
            ->requireThreshold();

        $salesOrderThresholdValueTransfer->getSalesOrderThresholdType()
            ->requireKey();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[] $salesOrderThresholdValueTransfers
     * @param string $thresholdGroup
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    protected function filterSalesOrderThresholdsByThresholdGroup(array $salesOrderThresholdValueTransfers, string $thresholdGroup): array
    {
        return array_filter($salesOrderThresholdValueTransfers, function (SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfers) use ($thresholdGroup) {
            return $salesOrderThresholdValueTransfers->getSalesOrderThresholdType()->getThresholdGroup() === $thresholdGroup;
        });
    }
}
