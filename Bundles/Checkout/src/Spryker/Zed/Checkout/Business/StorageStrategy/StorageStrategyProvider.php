<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StorageStrategy;

use Spryker\Client\Quote\Exception\StorageStrategyNotFound;
use Spryker\Zed\Quote\Business\QuoteFacade;

class StorageStrategyProvider implements StorageStrategyProviderInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacade
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface[]
     */
    protected $storageStrategyList;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade
     * @param \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface[] $storageStrategyList
     */
    public function __construct(QuoteFacade $quoteFacade, array $storageStrategyList)
    {
        $this->quoteFacade = $quoteFacade;
        $this->storageStrategyList = $storageStrategyList;
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface
     */
    public function provideStorage(): StorageStrategyInterface
    {
        $storageStrategy = $this->findStorageStrategy($this->quoteFacade->getStorageStrategy());

        return $storageStrategy;
    }

    /**
     * @param string $storageStrategyType
     *
     * @throws \Spryker\Client\Quote\Exception\StorageStrategyNotFound
     *
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface
     */
    protected function findStorageStrategy(string $storageStrategyType): StorageStrategyInterface
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
}
