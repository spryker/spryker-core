<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\QuoteStorageStrategy;

use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Cart\Exception\QuoteStorageStrategyPluginNotFound;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface[]
     */
    protected $quoteStorageStrategyPlugins;

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface $quoteClient
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface[] $quoteStorageStrategyPlugins
     */
    public function __construct(CartToQuoteInterface $quoteClient, array $quoteStorageStrategyPlugins)
    {
        $this->quoteClient = $quoteClient;
        $this->quoteStorageStrategyPlugins = $quoteStorageStrategyPlugins;
    }

    /**
     * @throws \Spryker\Client\Cart\Exception\QuoteStorageStrategyPluginNotFound
     *
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    public function provideStorage(): QuoteStorageStrategyPluginInterface
    {
        $storageStrategyType = $this->quoteClient->getStorageStrategy();
        foreach ($this->quoteStorageStrategyPlugins as $storageStrategy) {
            if ($storageStrategy->getStorageStrategy() === $storageStrategyType) {
                return $storageStrategy;
            }
        }

        throw new QuoteStorageStrategyPluginNotFound(
            sprintf(
                'There is no quote storage strategy with name: %s. ' .
                'It should be added to \Spryker\Client\Cart\CartDependencyProvider::getQuoteStorageStrategyPlugins()',
                $storageStrategyType
            )
        );
    }
}
