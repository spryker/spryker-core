<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote;

use Spryker\Client\CartNote\QuoteStorageStrategy\DatabaseQuoteStorageStrategy;
use Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface;
use Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyProvider;
use Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface;
use Spryker\Client\CartNote\QuoteStorageStrategy\SessionQuoteStorageStrategy;
use Spryker\Client\Kernel\AbstractFactory;

class CartNoteFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function getQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return $this->createQuoteStorageStrategyProvider()->provideStorage();
    }

    /**
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface
     */
    public function createQuoteStorageStrategyProvider(): QuoteStorageStrategyProviderInterface
    {
        return new QuoteStorageStrategyProvider(
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategyProviders()
        );
    }

    /**
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[]
     */
    protected function getQuoteStorageStrategyProviders(): array
    {
        return [
            $this->createSessionQuoteStorageStrategy(),
            $this->createDatabaseQuoteStorageStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function createSessionQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return new SessionQuoteStorageStrategy($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Client\CartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function createDatabaseQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return new DatabaseQuoteStorageStrategy(
            $this->getQuoteClient(),
            $this->getPersistentCartClient()
        );
    }

    /**
     * @return \Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(CartNoteDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\CartNote\Dependency\Client\CartNoteToPersistentCartClientInterface
     */
    public function getPersistentCartClient()
    {
        return $this->getProvidedDependency(CartNoteDependencyProvider::CLIENT_PERSISTENT_CART);
    }
}
