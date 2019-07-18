<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToLocaleBridge;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToPriceProductBridge;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToStorageBridge;

class ProductRelationDependencyProvider extends AbstractDependencyProvider
{
    public const KV_STORAGE = 'kv storage';
    public const CLIENT_LOCALE = 'locale client';
    public const CLIENT_PRICE_PRODUCT = 'price product client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addPriceProductClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::KV_STORAGE] = function (Container $container) {
            return new ProductRelationToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductRelationToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container)
    {
        $container[static::CLIENT_PRICE_PRODUCT] = function (Container $container) {
            return new ProductRelationToPriceProductBridge($container->getLocator()->priceProduct()->client());
        };

        return $container;
    }
}
