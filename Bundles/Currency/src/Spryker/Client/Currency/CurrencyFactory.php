<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;

class CurrencyFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->createCurrencyPersistence()->getCurrentCurrencyIsoCode()
        );
    }

    /**
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    public function createCurrencyPersistence()
    {
        return new CurrencyPersistence($this->getSessionClient(), $this->getStore());
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected function getInternationalization()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_SESSION);
    }

}
