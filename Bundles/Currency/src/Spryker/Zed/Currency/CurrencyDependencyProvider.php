<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency;

use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Intl\Intl;

class CurrencyDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE = 'store';
    const INTERNATIONALIZATION = 'internationalization';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addInternationalization($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return new CurrencyToStoreBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
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

}
