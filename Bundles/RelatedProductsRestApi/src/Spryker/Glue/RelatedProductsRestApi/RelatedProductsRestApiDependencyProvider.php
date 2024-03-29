<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientBridge;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientBridge;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientBridge;
use Spryker\Glue\RelatedProductsRestApi\Dependency\RestApiResource\RelatedProductsRestApiToProductsRestApiResourceBridge;

/**
 * @method \Spryker\Glue\RelatedProductsRestApi\RelatedProductsRestApiConfig getConfig()
 */
class RelatedProductsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_RELATION_STORAGE = 'CLIENT_PRODUCT_RELATION_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
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
        $container = $this->addProductsRestApiResource($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new RelatedProductsRestApiToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductRelationStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_RELATION_STORAGE, function (Container $container) {
            return new RelatedProductsRestApiToProductRelationStorageClientBridge(
                $container->getLocator()->productRelationStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new RelatedProductsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_PRODUCTS_REST_API, function (Container $container) {
            return new RelatedProductsRestApiToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource(),
            );
        });

        return $container;
    }
}
