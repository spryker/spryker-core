<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductResourceAliasStorageClientBridge;

class ProductsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_RESOURCE_ALIAS_STORAGE = 'CLIENT_PRODUCT_RESOURCE_ALIAS_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductResourceAliasStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductResourceAliasStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_RESOURCE_ALIAS_STORAGE] = function (Container $container) {
            return new ProductsRestApiToProductResourceAliasStorageClientBridge($container->getLocator()->productResourceAliasStorage()->client());
        };

        return $container;
    }
}
