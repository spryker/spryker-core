<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientBridge;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientBridge;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToStorageClientBridge;

class ProductOptionDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_PRICE = 'CLIENT_PRICE';
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addPriceClient($container);
        $container = $this->addCurrencyClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductOptionToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceClient(Container $container)
    {
        $container[static::CLIENT_PRICE] = function (Container $container) {
            return new ProductOptionToPriceClientBridge($container->getLocator()->price()->client());
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
            return new ProductOptionToCurrencyClientBridge($container->getLocator()->currency()->client());
        };

        return $container;
    }
}
