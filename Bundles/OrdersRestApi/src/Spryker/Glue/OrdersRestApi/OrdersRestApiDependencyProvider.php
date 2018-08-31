<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToProductBundleClientBridge;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientBridge;

class OrdersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SALES = 'CLIENT_SALES';
    public const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSalesClient($container);
        $container = $this->addProductBundleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container[static::CLIENT_SALES] = function (Container $container) {
            return new OrdersRestApiToSalesClientBridge($container->getLocator()->sales()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_BUNDLE] = function (Container $container) {
            return new OrdersRestApiToProductBundleClientBridge($container->getLocator()->productBundle()->client());
        };

        return $container;
    }
}
