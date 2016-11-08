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

class ProductDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_LOCALE = 'CLIENT_LOCALE';
    const KV_STORAGE = 'KV_STORAGE';
    const UTIL_ENCODING_CLIENT = 'UTIL_ENCODING_FACADE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return new ProductToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[self::CLIENT_LOCALE] = function (Container $container) {
            return new ProductToLocaleBridge($container->getLocator()->locale()->client());
        };

        $container[self::UTIL_ENCODING_CLIENT] = function (Container $container) {
            return $container->getLocator()->utilEncoding()->client();
        };

        return $container;
    }

}
