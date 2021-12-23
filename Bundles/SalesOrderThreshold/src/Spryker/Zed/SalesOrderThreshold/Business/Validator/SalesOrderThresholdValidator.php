<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig;

class SalesOrderThresholdValidator implements SalesOrderThresholdValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_PARAMETER_THRESHOLD = '{{threshold}}';

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface
     */
    protected $salesOrderThresholdDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig
     */
    protected $salesOrderThresholdConfig;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig $salesOrderThresholdConfig
     */
    public function __construct(
        SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver,
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver,
        SalesOrderThresholdToMoneyFacadeInterface $moneyFacade,
        SalesOrderThresholdConfig $salesOrderThresholdConfig
    ) {
        $this->salesOrderThresholdDataSourceStrategyResolver = $salesOrderThresholdDataSourceStrategyResolver;
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
        $this->moneyFacade = $moneyFacade;
        $this->salesOrderThresholdConfig = $salesOrderThresholdConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateSalesOrderThresholdsCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $salesOrderThresholdValueTransfers = $this->salesOrderThresholdDataSourceStrategyResolver
            ->findApplicableThresholds($checkoutDataTransfer->getQuoteOrFail());

        if (!$salesOrderThresholdValueTransfers) {
            return $checkoutResponseTransfer;
        }

        $salesOrderThresholdValueTransfers = $this->filterSalesOrderThresholdsByThresholdKeys(
            $salesOrderThresholdValueTransfers,
            $this->salesOrderThresholdConfig->getApplicableThresholdStrategies(),
        );

        if (!$salesOrderThresholdValueTransfers) {
            return $checkoutResponseTransfer;
        }

        return $this->addErrorMessagesToCheckoutResponse($checkoutDataTransfer, $checkoutResponseTransfer, $salesOrderThresholdValueTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer> $salesOrderThresholdValueTransfers
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorMessagesToCheckoutResponse(
        CheckoutDataTransfer $checkoutDataTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $salesOrderThresholdValueTransfers
    ): CheckoutResponseTransfer {
        foreach ($salesOrderThresholdValueTransfers as $salesOrderThresholdValueTransfer) {
            $this->addErrorMessageToCheckoutResponse(
                $checkoutResponseTransfer,
                $checkoutDataTransfer->getQuoteOrFail()->getCurrencyOrFail(),
                $salesOrderThresholdValueTransfer,
            );
        }

        if ($checkoutResponseTransfer->getErrors()->count() > 0) {
            $checkoutResponseTransfer->setIsSuccess(false);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorMessageToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        CurrencyTransfer $currencyTransfer,
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
    ): CheckoutResponseTransfer {
        $salesOrderThresholdValueTransferStrategy = $this->salesOrderThresholdStrategyResolver
            ->resolveSalesOrderThresholdStrategy($salesOrderThresholdValueTransfer->getSalesOrderThresholdTypeOrFail()->getKeyOrFail());

        if (!$salesOrderThresholdValueTransferStrategy->isApplicable($salesOrderThresholdValueTransfer)) {
            return $checkoutResponseTransfer;
        }

        return $checkoutResponseTransfer->addError(
            $this->createCheckoutErrorTransfer($salesOrderThresholdValueTransfer, $currencyTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer,
        CurrencyTransfer $currencyTransfer
    ): CheckoutErrorTransfer {
        return (new CheckoutErrorTransfer())
            ->setMessage($salesOrderThresholdValueTransfer->getMessageGlossaryKeyOrFail())
            ->setParameters([
                static::GLOSSARY_PARAMETER_THRESHOLD => $this->moneyFacade->formatWithSymbol(
                    $this->createMoneyTransfer($currencyTransfer, (string)$salesOrderThresholdValueTransfer->getThreshold()),
                ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param string $amount
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createMoneyTransfer(
        CurrencyTransfer $currencyTransfer,
        string $amount
    ): MoneyTransfer {
        return (new MoneyTransfer())
            ->setAmount($amount)
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer> $salesOrderThresholdValueTransfers
     * @param array<string> $thresholdKeys
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer>
     */
    protected function filterSalesOrderThresholdsByThresholdKeys(array $salesOrderThresholdValueTransfers, array $thresholdKeys): array
    {
        return array_filter($salesOrderThresholdValueTransfers, function (SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer) use ($thresholdKeys) {
            return in_array($salesOrderThresholdValueTransfer->getSalesOrderThresholdTypeOrFail()->getKey(), $thresholdKeys, true);
        });
    }
}
