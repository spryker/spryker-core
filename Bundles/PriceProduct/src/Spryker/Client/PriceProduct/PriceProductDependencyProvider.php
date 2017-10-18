<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyBridge;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceBridge;

class PriceProductDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_PRICE = 'PRICE_CLIENT';
    const CLIENT_CURRENCY = 'CURRENCY_CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addPriceProductClient($container);
        $container = $this->addCurrencyClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container)
    {
        $container[static::CLIENT_PRICE] = function (Container $container) {
            return new PriceProductToPriceBridge($container->getLocator()->price()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container)
    {
        $container[static::CLIENT_CURRENCY] = function (Container $container) {
            return new PriceProductToCurrencyBridge($container->getLocator()->currency()->client());
        };

        return $container;
    }

}
