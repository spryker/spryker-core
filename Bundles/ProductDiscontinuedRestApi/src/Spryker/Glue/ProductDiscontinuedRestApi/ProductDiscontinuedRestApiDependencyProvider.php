<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductDiscontinuedRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductDiscontinuedRestApi\ProductDiscontinuedRestApiConfig getConfig()
 */
class ProductDiscontinuedRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_DISCONTINUED_STORAGE = 'CLIENT_PRODUCT_DISCONTINUED_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductDiscontinuedStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductDiscontinuedStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_DISCONTINUED_STORAGE] = function (Container $container) {
            return new ProductDiscontinuedRestApiToProductDiscontinuedStorageClientBridge(
                $container->getLocator()->productDiscontinuedStorage()->client()
            );
        };

        return $container;
    }
}
