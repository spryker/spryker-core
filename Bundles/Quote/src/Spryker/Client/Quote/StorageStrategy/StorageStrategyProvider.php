<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Spryker\Client\Quote\Exception\StorageStrategyNotFound;
use Spryker\Client\Quote\QuoteConfig;
use Spryker\Shared\Quote\QuoteConfig as SharedConfig;

class StorageStrategyProvider implements StorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteConfig
     */
    protected $quoteConfig;

    /**
     * @var \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface[]
     */
    protected $storageStrategyList;

    /**
     * @param \Spryker\Client\Quote\QuoteConfig $quoteConfig
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface[] $storageStrategyList
     */
    public function __construct(QuoteConfig $quoteConfig, array $storageStrategyList)
    {
        $this->quoteConfig = $quoteConfig;
        $this->storageStrategyList = $storageStrategyList;
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    public function provideStorage(): StorageStrategyInterface
    {
        $storageStrategy = $this->findStorageStrategy($this->quoteConfig->getStorageStrategy());

        if (!$storageStrategy->isAllowed()) {
            $storageStrategy = $this->getDefaultStorageStrategy();
        }

        return $storageStrategy;
    }

    /**
     * @param string $storageStrategyType
     *
     * @throws \Spryker\Client\Quote\Exception\StorageStrategyNotFound
     *
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected function findStorageStrategy($storageStrategyType): StorageStrategyInterface
    {
        foreach ($this->storageStrategyList as $storageStrategy) {
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

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected function getDefaultStorageStrategy(): StorageStrategyInterface
    {
        return $this->findStorageStrategy(SharedConfig::STORAGE_STRATEGY_SESSION);
    }
}
