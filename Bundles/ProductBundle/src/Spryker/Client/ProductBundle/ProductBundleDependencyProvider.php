<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ProductBundleDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_PRODUCT_BUNDLE = 'SERVICE_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addProductBundleService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductBundleService(Container $container): Container
    {
        $container[static::SERVICE_PRODUCT_BUNDLE] = function (Container $container) {
            return $container->getLocator()->productBundle()->service();
        };

        return $container;
    }
}
