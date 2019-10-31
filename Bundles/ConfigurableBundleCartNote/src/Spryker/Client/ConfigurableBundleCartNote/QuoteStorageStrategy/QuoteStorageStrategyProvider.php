<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface;
use Spryker\Client\ConfigurableBundleCartNote\Exception\QuoteStorageStrategyNotFound;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[]
     */
    protected $quoteStorageStrategies;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[] $quoteStorageStrategies
     */
    public function __construct(ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient, array $quoteStorageStrategies)
    {
        $this->quoteClient = $quoteClient;
        $this->quoteStorageStrategies = $quoteStorageStrategies;
    }

    /**
     * @throws \Spryker\Client\ConfigurableBundleCartNote\Exception\QuoteStorageStrategyNotFound
     *
     * @return \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function provideStorage(): QuoteStorageStrategyInterface
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
