<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\QuoteStorageStrategy;

use Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface;
use Spryker\Client\CartNote\Exception\QuoteStorageStrategyPluginNotFound;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[]
     */
    protected $quoteStorageStrategyPlugins;

    /**
     * @var \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[] $quoteStorageStrategyPlugins
     */
    public function __construct(CartNoteToQuoteClientInterface $quoteClient, array $quoteStorageStrategyPlugins)
    {
        $this->quoteClient = $quoteClient;
        $this->quoteStorageStrategyPlugins = $quoteStorageStrategyPlugins;
    }

    /**
     * @throws \Spryker\Client\CartNote\Exception\QuoteStorageStrategyPluginNotFound
     *
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function provideStorage(): QuoteStorageStrategyInterface
    {
        $storageStrategyType = $this->quoteClient->getStorageStrategy();
        foreach ($this->quoteStorageStrategyPlugins as $storageStrategy) {
            if ($storageStrategy->getStorageStrategy() === $storageStrategyType) {
                return $storageStrategy;
            }
        }

        throw new QuoteStorageStrategyPluginNotFound(
            sprintf(
                'There is no quote storage strategy with name: %s. ',
                $storageStrategyType
            )
        );
    }
}
