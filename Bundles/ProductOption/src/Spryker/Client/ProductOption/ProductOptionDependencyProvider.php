<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToStorageBridge;

class ProductOptionDependencyProvider extends AbstractDependencyProvider
{

    const KV_STORAGE = 'kv storage';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return new ProductOptionToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

}
