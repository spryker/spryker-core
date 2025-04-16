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
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;
use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;

class CurrencyFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder(): CurrencyBuilderInterface
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->getStoreClient()->getCurrentStore()->getDefaultCurrencyIsoCodeOrFail(),
            $this->createCurrencyPersistence()->getCurrentCurrencyIsoCode(),
        );
    }

    /**
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    public function createCurrencyPersistence(): CurrencyPersistenceInterface
    {
        return new CurrencyPersistence($this->getSessionClient(), $this->getStoreClient()->getCurrentStore()->getDefaultCurrencyIsoCodeOrFail());
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
            $this->getStoreClient(),
            $this->getCurrentCurrencyIsoCodePreCheckPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface
     */
    public function createCurrencyPostChangePluginExecutor(): CurrencyPostChangePluginExecutorInterface
    {
        return new CurrencyPostChangePluginExecutor(
            $this->getZedRequestClient(),
            $this->getCurrencyPostChangePlugins(),
        );
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    public function getInternationalization(): CurrencyToInternationalizationInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }

    /**
     * @return \Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    public function getStoreClient(): CurrencyToStoreClientInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    public function getSessionClient(): CurrencyToSessionInterface
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
     * @return list<\Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface>
     */
    public function getCurrencyPostChangePlugins(): array
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::PLUGINS_CURRENCY_POST_CHANGE);
    }

    /**
     * @return list<\Spryker\Client\CurrencyExtension\Dependency\Plugin\CurrentCurrencyIsoCodePreCheckPluginInterface>
     */
    public function getCurrentCurrencyIsoCodePreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::PLUGINS_CURRENT_CURRENCY_ISO_CODE_PRE_CHECK);
    }
}
