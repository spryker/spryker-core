<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\ThresholdMessenger;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface;

class ThresholdMessenger implements ThresholdMessengerInterface
{
    protected const THRESHOLD_GLOSSARY_PARAMETER = '{{threshold}}';
    protected const FEE_GLOSSARY_PARAMETER = '{{fee}}';

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface
     */
    protected $minimumOrderValueDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     */
    public function __construct(
        MinimumOrderValueToMessengerFacadeInterface $messengerFacade,
        MinimumOrderValueToMoneyFacadeInterface $moneyFacade,
        MinimumOrderValueDataSourceStrategyResolverInterface $minimumOrderValueDataSourceStrategyResolver,
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->moneyFacade = $moneyFacade;
        $this->minimumOrderValueDataSourceStrategyResolver = $minimumOrderValueDataSourceStrategyResolver;
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addMinimumOrderValueMessages(
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
        $minimumOrderValueThresholdTransfers = $this->filterMinimumOrderValuesByThresholdGroup(
            $this->minimumOrderValueDataSourceStrategyResolver->findApplicableThresholds($quoteTransfer),
            MinimumOrderValueConfig::GROUP_SOFT
        );

        $thresholdMessages = [];
        foreach ($minimumOrderValueThresholdTransfers as $minimumOrderValueThresholdTransfer) {
            $minimumOrderValueStrategy = $this->minimumOrderValueStrategyResolver->resolveMinimumOrderValueStrategy(
                $minimumOrderValueThresholdTransfer->getMinimumOrderValueType()->getKey()
            );

            if (!$minimumOrderValueStrategy->isApplicable($minimumOrderValueThresholdTransfer)
            ) {
                continue;
            }

            $thresholdMessages[$minimumOrderValueThresholdTransfer->getMessageGlossaryKey()] =
                $this->createMessageTransfer(
                    $minimumOrderValueThresholdTransfer->getMessageGlossaryKey(),
                    (string)$minimumOrderValueThresholdTransfer->getThreshold(),
                    (string)$minimumOrderValueStrategy->calculateFee($minimumOrderValueThresholdTransfer),
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
