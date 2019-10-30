<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote;

use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface;
use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface;
use Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface;
use Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyProvider;
use Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface;
use Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\SessionQuoteStorageStrategy;
use Spryker\Client\Kernel\AbstractFactory;

class ConfigurableBundleCartNoteFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function getQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return $this->createQuoteStorageStrategyProvider()->provideStorage();
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface
     */
    public function createQuoteStorageStrategyProvider(): QuoteStorageStrategyProviderInterface
    {
        return new QuoteStorageStrategyProvider(
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategies()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface[]
     */
    public function getQuoteStorageStrategies(): array
    {
        return [
            $this->createSessionQuoteStorageStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy\QuoteStorageStrategyInterface
     */
    public function createSessionQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return new SessionQuoteStorageStrategy($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface
     */
    public function getZedRequestClient(): ConfigurableBundleCartNoteToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartNoteDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface
     */
    public function getQuoteClient(): ConfigurableBundleCartNoteToQuoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartNoteDependencyProvider::CLIENT_QUOTE);
    }
}
