<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageBridge;

class ProductImageCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductImageCartConnectorToProductImageBridge($container->getLocator()->productImage()->facade());
        };

        return $container;
    }
}
