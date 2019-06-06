<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientBridge;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientBridge;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductStorageClientBridge;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\UpSellingProductsRestApi\UpSellingProductsRestApiConfig getConfig()
 */
class UpSellingProductsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_RELATION_STORAGE = 'CLIENT_PRODUCT_RELATION_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_CARTS_REST_API = 'CLIENT_CARTS_REST_API';

    public const RESOURCE_PRODUCTS_REST_API = 'RESOURCE_PRODUCTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductRelationStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCartsRestApiClient($container);
        $container = $this->addProductsRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductRelationStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_RELATION_STORAGE] = function (Container $container) {
            return new UpSellingProductsRestApiToProductRelationStorageClientBridge(
                $container->getLocator()->productRelationStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new UpSellingProductsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartsRestApiClient(Container $container): Container
    {
        $container[static::CLIENT_CARTS_REST_API] = function (Container $container) {
            return new UpSellingProductsRestApiToCartsRestApiClientBridge(
                $container->getLocator()->cartsRestApi()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductsRestApiResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCTS_REST_API] = function (Container $container) {
            return new UpSellingProductsRestApiToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource()
            );
        };

        return $container;
    }
}
