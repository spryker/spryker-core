<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientBridge;

class ProductImageSetsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_IMAGE_STORAGE = 'CLIENT_PRODUCT_IMAGE_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductImageStorageClientDependency($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductImageStorageClientDependency(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_IMAGE_STORAGE] = function (Container $container) {
            return new ProductImageSetsRestApiToProductImageResourceAliasStorageClientBridge(
                $container->getLocator()->productImageResourceAliasStorage()->client()
            );
        };

        return $container;
    }
}
