<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientBridge;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ProductAlternativesRestApi\ProductAlternativesRestApiConfig getConfig()
 */
class ProductAlternativesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_ALTERNATIVE_STORAGE = 'CLIENT_PRODUCT_ALTERNATIVE_STORAGE';
    public const RESOURCE_PRODUCTS_REST_API = 'RESOURCE_PRODUCTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductAlternativeStorageClient($container);
        $container = $this->addProductsRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductAlternativeStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_ALTERNATIVE_STORAGE] = function (Container $container) {
            return new ProductAlternativesRestApiToProductAlternativeStorageClientBridge(
                $container->getLocator()->productAlternativeStorage()->client()
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
            return new ProductAlternativesRestApiToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource()
            );
        };

        return $container;
    }
}
