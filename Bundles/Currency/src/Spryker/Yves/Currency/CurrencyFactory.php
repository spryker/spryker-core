<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;
use Spryker\Yves\Currency\CurrencyChange\CurrencyPostChangePluginExecutor;
use Spryker\Yves\Kernel\AbstractFactory;

class CurrencyFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->getStore()->getDefaultCurrencyCode(),
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
     * @return \Spryker\Yves\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface
     */
    public function createCurrencyPostChangePluginExecutor()
    {
        return new CurrencyPostChangePluginExecutor(
            $this->getCurrencyPostChangePlugins(),
            $this->createCurrencyPersistence(),
            $this->getZedRequestClient(),
            $this->getMessengerClient()
        );
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
    public function getStore()
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

    /**
     * @return \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected function getCurrencyPostChangePlugins()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CURRENCY_POST_CHANGE_PLUGINS);
    }

    /**
     * @return \Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Yves\Currency\Dependency\Client\CurrencyToMessengerClientInterface
     */
    protected function getMessengerClient()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_MESSENGER);
    }
}
