<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Product\Dependency\Client\ProductToLocaleBridge;
use Spryker\Client\Product\Dependency\Client\ProductToStorageBridge;
use Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingBridge;

class ProductDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_LOCALE = 'client locale';
    const KV_STORAGE = 'kv storage';
    const SERVICE_ENCODING = 'util encoding';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::KV_STORAGE] = function (Container $container) {
            return new ProductToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductToLocaleBridge($container->getLocator()->locale()->client());
        };

        $container[static::SERVICE_ENCODING] = function (Container $container) {
            return new ProductToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
