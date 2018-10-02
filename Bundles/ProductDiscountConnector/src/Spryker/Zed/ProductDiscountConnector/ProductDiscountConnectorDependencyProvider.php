<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountBridge;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleBridge;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductBridge;

class ProductDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return new ProductDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductDiscountConnectorToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductDiscountConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
