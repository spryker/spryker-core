<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

use Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutor;
use Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface;
use Spryker\Client\Currency\CurrencyChange\CurrencyUpdater;
use Spryker\Client\Currency\CurrencyChange\CurrencyUpdaterInterface;
use Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface;
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
     * @return \Spryker\Client\Currency\CurrencyChange\CurrencyUpdaterInterface
     */
    public function createCurrencyUpdater(): CurrencyUpdaterInterface
    {
        return new CurrencyUpdater(
            $this->createCurrencyBuilder(),
            $this->createCurrencyPostChangePluginExecutor(),
            $this->createCurrencyPersistence(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface
     */
    public function createCurrencyPostChangePluginExecutor(): CurrencyPostChangePluginExecutorInterface
    {
        return new CurrencyPostChangePluginExecutor(
            $this->getZedRequestClient(),
            $this->getCurrencyPostChangePlugins()
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
    protected function getStore()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    protected function getStoreClient()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface
     */
    public function getZedRequestClient(): CurrencyToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected function getCurrencyPostChangePlugins(): array
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::PLUGINS_CURRENCY_POST_CHANGE);
    }
}
