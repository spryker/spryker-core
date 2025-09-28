<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Quote;

use Spryker\Client\Quote\QuoteClientInterface;
use SprykerFeature\Shared\SelfServicePortal\Exception\QuoteStorageStrategyNotFound;

class QuoteStorageStrategyProvider implements QuoteStorageStrategyProviderInterface
{
    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     * @param array<\SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyInterface> $quoteStorageStrategies
     */
    public function __construct(
        protected QuoteClientInterface $quoteClient,
        protected array $quoteStorageStrategies
    ) {
    }

    public function provideStorage(): QuoteStorageStrategyInterface
    {
        $storageStrategyType = $this->quoteClient->getStorageStrategy();
        foreach ($this->quoteStorageStrategies as $storageStrategy) {
            if ($storageStrategy->getStorageStrategy() === $storageStrategyType) {
                return $storageStrategy;
            }
        }

        throw new QuoteStorageStrategyNotFound(
            sprintf(
                'There is no quote storage strategy with name: %s. ',
                $storageStrategyType,
            ),
        );
    }
}
