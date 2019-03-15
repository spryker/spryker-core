<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToUtilQuantityServiceBridge;

class ProductBundleDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_UTIL_QUANTITY = 'SERVICE_UTIL_QUANTITY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addUtilQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilQuantityService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_QUANTITY] = function (Container $container) {
            return new ProductBundleToUtilQuantityServiceBridge($container->getLocator()->utilQuantity()->service());
        };

        return $container;
    }
}
