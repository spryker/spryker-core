<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Spryker\Client\Currency\Plugin\CurrencyPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Quote\Dependency\Plugin\QuoteToCurrencyBridge;

class QuoteDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_SESSION = 'session client';
    const CURRENCY_PLUGIN = 'currency plugin';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSessionClient($container);
        $container = $this->addCurrencyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyPlugin(Container $container)
    {
        $container[static::CURRENCY_PLUGIN] = function (Container $container) {
            return new QuoteToCurrencyBridge(new CurrencyPlugin());
        };

        return $container;
    }

}
