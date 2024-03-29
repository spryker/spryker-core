<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductCategoryStorageClientBridge;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductStorageClientBridge;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToStoreClientBridge;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ProductsCategoriesResourceRelationship\ProductsCategoriesResourceRelationshipConfig getConfig()
 */
class ProductsCategoriesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_CATEGORY = 'RESOURCE_CATEGORY';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CATEGORY_STORAGE = 'CLIENT_PRODUCT_CATEGORY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        parent::provideDependencies($container);
        $container = $this->addCategoriesResource($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addProductCategoryStorageClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCategoriesResource(Container $container): Container
    {
        $container->set(static::RESOURCE_CATEGORY, function (Container $container) {
            return new ProductsCategoriesResourceRelationToCategoriesRestApiResourceBridge(
                $container->getLocator()->categoriesRestApi()->resource(),
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
            return new ProductsCategoriesResourceRelationshipToProductStorageClientBridge(
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
    protected function addProductCategoryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CATEGORY_STORAGE, function (Container $container) {
            return new ProductsCategoriesResourceRelationshipToProductCategoryStorageClientBridge(
                $container->getLocator()->productCategoryStorage()->client(),
            );
        });

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
            return new ProductsCategoriesResourceRelationshipToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }
}
