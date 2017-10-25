<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency;

use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Currency\Dependency\Client\CurrencyToSessionBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Symfony\Component\Intl\Intl;

class CurrencyDependencyProvider extends AbstractBundleDependencyProvider
{
    const STORE = 'store';
    const INTERNATIONALIZATION = 'internationalization';
    const CLIENT_SESSION = 'CLIENT_SESSION';
    const CURRENCY_POST_CHANGE_PLUGINS = 'CURRENCY_POST_CHANGE_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addInternationalization($container);
        $container = $this->addSessionClient($container);
        $container = $this->addCurrencyPostChangePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addInternationalization(Container $container)
    {
        $container[static::INTERNATIONALIZATION] = function () {
            $currencyToInternationalizationBridge = new CurrencyToInternationalizationBridge(
                Intl::getCurrencyBundle()
            );

            return $currencyToInternationalizationBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new CurrencyToSessionBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyPostChangePlugins(Container $container)
    {
        $container[static::CURRENCY_POST_CHANGE_PLUGINS] = function () {
            return $this->getCurrencyPostChangePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected function getCurrencyPostChangePlugins()
    {
        return [];
    }
}
