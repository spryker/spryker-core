<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\ThresholdMessenger;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface;

class ThresholdMessenger implements ThresholdMessengerInterface
{
    protected const THRESHOLD_GLOSSARY_PARAMETER = '{{threshold}}';
    protected const FEE_GLOSSARY_PARAMETER = '{{fee}}';

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface
     */
    protected $salesOrderThresholdDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
     */
    public function __construct(
        SalesOrderThresholdToMessengerFacadeInterface $messengerFacade,
        SalesOrderThresholdToMoneyFacadeInterface $moneyFacade,
        SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver,
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->moneyFacade = $moneyFacade;
        $this->salesOrderThresholdDataSourceStrategyResolver = $salesOrderThresholdDataSourceStrategyResolver;
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSalesOrderThresholdMessages(
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $thresholdMessages = $this->getMessagesForThresholds($quoteTransfer);
        foreach ($thresholdMessages as $thresholdMessage) {
            $this->messengerFacade->addInfoMessage($thresholdMessage);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function getMessagesForThresholds(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderThresholdValueTransfers = $this->filterSalesOrderThresholdsByThresholdGroup(
            $this->salesOrderThresholdDataSourceStrategyResolver->findApplicableThresholds($quoteTransfer),
            SalesOrderThresholdConfig::GROUP_SOFT
        );

        $thresholdMessages = [];
        foreach ($salesOrderThresholdValueTransfers as $salesOrderThresholdValueTransfer) {
            $salesOrderThresholdStrategy = $this->salesOrderThresholdStrategyResolver->resolveSalesOrderThresholdStrategy(
                $salesOrderThresholdValueTransfer->getSalesOrderThresholdType()->getKey()
            );

            if (!$salesOrderThresholdStrategy->isApplicable($salesOrderThresholdValueTransfer)) {
                continue;
            }

            $thresholdMessages[$salesOrderThresholdValueTransfer->getMessageGlossaryKey()] =
                $this->createMessageTransfer(
                    $salesOrderThresholdValueTransfer->getMessageGlossaryKey(),
                    (string)$salesOrderThresholdValueTransfer->getThreshold(),
                    (string)$salesOrderThresholdStrategy->calculateFee($salesOrderThresholdValueTransfer),
                    $quoteTransfer->getCurrency()
                );
        }

        return $thresholdMessages;
    }

    /**
     * @param string $messageGlossaryKey
     * @param string $threshold
     * @param string $fee
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(
        string $messageGlossaryKey,
        string $threshold,
        string $fee,
        CurrencyTransfer $currencyTransfer
    ): MessageTransfer {
        $messageParams = [
            static::THRESHOLD_GLOSSARY_PARAMETER => $this->moneyFacade->formatWithSymbol(
                $this->createMoneyTransfer($threshold, $currencyTransfer)
            ),
        ];

        if ($fee) {
            $messageParams[static::FEE_GLOSSARY_PARAMETER] = $this->moneyFacade->formatWithSymbol(
                $this->createMoneyTransfer($fee, $currencyTransfer)
            );
        }

        return (new MessageTransfer())
            ->setValue($messageGlossaryKey)
            ->setParameters($messageParams);
    }

    /**
     * @param string $moneyValue
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function createMoneyTransfer(
        string $moneyValue,
        CurrencyTransfer $currencyTransfer
    ): MoneyTransfer {
        return (new MoneyTransfer())
            ->setAmount($moneyValue)
            ->setCurrency($currencyTransfer);
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
