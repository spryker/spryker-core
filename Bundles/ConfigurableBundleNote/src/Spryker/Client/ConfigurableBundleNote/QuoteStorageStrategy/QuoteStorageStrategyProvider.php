<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy;

use Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToQuoteClientInterface;
use Spryker\Client\ConfigurableBundleNote\Exception\QuoteStorageStrategyNotFound;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[]
     */
    protected $quoteStorageStrategies;

    /**
     * @param \Spryker\Client\ConfigurableBundleNote\Dependency\Client\ConfigurableBundleNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[] $quoteStorageStrategies
     */
    public function __construct(ConfigurableBundleNoteToQuoteClientInterface $quoteClient, array $quoteStorageStrategies)
    {
        $this->quoteClient = $quoteClient;
        $this->quoteStorageStrategies = $quoteStorageStrategies;
    }

    /**
     * @throws \Spryker\Client\ConfigurableBundleNote\Exception\QuoteStorageStrategyNotFound
     *
     * @return \Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function provideStorageStrategy(): QuoteStorageStrategyInterface
    {
        $currentQuoteStorageStrategyType = $this->quoteClient->getStorageStrategy();
        foreach ($this->quoteStorageStrategies as $quoteStorageStrategy) {
            if ($quoteStorageStrategy->getStorageStrategy() === $currentQuoteStorageStrategyType) {
                return $quoteStorageStrategy;
            }
        }

        throw new QuoteStorageStrategyNotFound(
            sprintf(
                'There is no quote storage strategy with name: %s.',
                $currentQuoteStorageStrategyType
            )
        );
    }
}
