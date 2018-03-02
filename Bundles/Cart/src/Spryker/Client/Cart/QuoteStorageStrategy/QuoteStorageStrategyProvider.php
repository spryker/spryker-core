<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\QuoteStorageStrategy;

use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Quote\Exception\StorageStrategyNotFound;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface[]
     */
    protected $quoteStorageStrategyPlugins;

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface $quoteClient
     * @param \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface[] $quoteStorageStrategyPlugins
     */
    public function __construct(
        CartToQuoteInterface $quoteClient,
        array $quoteStorageStrategyPlugins
    ) {
        $this->quoteStorageStrategyPlugins = $quoteStorageStrategyPlugins;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    public function provideStorage(): QuoteStorageStrategyPluginInterface
    {
        $storageStrategy = $this->findStorageStrategy($this->quoteClient->getStorageStrategy());

        return $storageStrategy;
    }

    /**
     * @param string $storageStrategyType
     *
     * @throws \Spryker\Client\Quote\Exception\StorageStrategyNotFound
     *
     * @return \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    protected function findStorageStrategy($storageStrategyType): QuoteStorageStrategyPluginInterface
    {
        foreach ($this->quoteStorageStrategyPlugins as $storageStrategy) {
            if ($storageStrategy->getStorageStrategy() === $storageStrategyType) {
                return $storageStrategy;
            }
        }

        throw new StorageStrategyNotFound(
            sprintf(
                'There is no quote storage strategy with name: %s',
                $storageStrategyType
            )
        );
    }
}
