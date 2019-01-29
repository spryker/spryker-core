<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi;

use Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductAlternativeStorageClientBridge;
use Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductStorageClientBridge;
use Spryker\Glue\AlternativeProductsRestApi\Dependency\Resource\AlternativeProductsRestApiToProductsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\AlternativeProductsRestApi\AlternativeProductsRestApiConfig getConfig()
 */
class AlternativeProductsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_ALTERNATIVE_STORAGE = 'CLIENT_PRODUCT_ALTERNATIVE_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

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
        $container = $this->addProductStorageClient($container);
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
            return new AlternativeProductsRestApiToProductAlternativeStorageClientBridge(
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
            return new AlternativeProductsRestApiToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource()
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
            return new AlternativeProductsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }
}
