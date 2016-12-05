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

    const CLIENT_LOCALE = 'client locale';
    const KV_STORAGE = 'kv storage';

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

        return $container;
    }

}
