<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToLocaleBridge;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToStorageBridge;

class ProductRelationDependencyProvider extends AbstractDependencyProvider
{
    const KV_STORAGE = 'kv storage';
    const CLIENT_LOCALE = 'locale client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return new ProductRelationToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductRelationToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
