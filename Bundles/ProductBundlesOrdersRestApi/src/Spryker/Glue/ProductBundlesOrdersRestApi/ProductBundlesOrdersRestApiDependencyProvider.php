<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesOrdersRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ProductBundlesOrdersRestApi\ProductBundlesOrdersRestApiConfig getConfig()
 */
class ProductBundlesOrdersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_ORDERS_REST_API = 'RESOURCE_ORDERS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addOrdersRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOrdersRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_ORDERS_REST_API, function (Container $container) {
            return new ProductBundlesOrdersRestApiToOrdersRestApiResourceBridge(
                $container->getLocator()->ordersRestApi()->resource()
            );
        });

        return $container;
    }
}
