<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 */
class CurrencyDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'STORE_FACADE';

    /**
     * @var string
     */
    public const CURRENCY_CURRENT = 'CURRENCY_CURRENT';

    /**
     * @var string
     */
    public const INTERNATIONALIZATION = 'internationalization';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addStoreFacade($container);
        $container = $this->addInternationalization($container);
        $container = $this->addCurrentCurrency($container);
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addInternationalization($container);
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addInternationalization(Container $container): Container
    {
        $container->set(static::INTERNATIONALIZATION, function () {
            return new CurrencyToInternationalizationBridge();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrentCurrency(Container $container): Container
    {
        $container->set(static::CURRENCY_CURRENT, function (Container $container) {
            if ($container->hasApplicationService(static::SERVICE_CURRENCY)) {
                return $container->getApplicationService(static::SERVICE_CURRENCY);
            }

            return $this->getStoreCurrentCurrency();
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @return string
     */
    protected function getStoreCurrentCurrency(): string
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new CurrencyToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function (): SpyStoreQuery {
            return SpyStoreQuery::create();
        }));

        return $container;
    }
}
