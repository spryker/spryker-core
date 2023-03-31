<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;
use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Currency\CurrencyClientInterface getClient()
 */
class CurrencyFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder(): CurrencyBuilderInterface
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->getClient()->getCurrencyIsoCodes()[0],
            $this->createCurrencyPersistence()->getCurrentCurrencyIsoCode(),
        );
    }

    /**
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    public function createCurrencyPersistence(): CurrencyPersistenceInterface
    {
        return new CurrencyPersistence($this->getSessionClient(), $this->getClient()->getCurrencyIsoCodes()[0]);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    public function getInternationalization(): CurrencyToInternationalizationInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    public function getSessionClient(): CurrencyToSessionInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_SESSION);
    }
}
