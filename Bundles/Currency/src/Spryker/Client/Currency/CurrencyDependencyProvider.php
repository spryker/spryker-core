<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\Intl\Intl;

class CurrencyDependencyProvider extends AbstractDependencyProvider
{

    const STORE = 'store';
    const INTERNATIONALIZATION = 'internationalization';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container|void
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addInternationalization($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
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
