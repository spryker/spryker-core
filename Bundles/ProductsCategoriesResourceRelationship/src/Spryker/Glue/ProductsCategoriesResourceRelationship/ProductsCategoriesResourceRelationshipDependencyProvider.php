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
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiResourceBridge;

class ProductsCategoriesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_CATEGORY = 'RESOURCE_CATEGORY';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_PRODUCT_CATEGORY_STORAGE = 'CLIENT_PRODUCT_CATEGORY_STORAGE';

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

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCategoriesResource(Container $container): Container
    {
        $container[static::RESOURCE_CATEGORY] = function (Container $container) {
            return new ProductsCategoriesResourceRelationToCategoriesRestApiResourceBridge(
                $container->getLocator()->categoriesRestApi()->resource()
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
            return new ProductsCategoriesResourceRelationshipToProductStorageClientBridge(
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
    protected function addProductCategoryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_CATEGORY_STORAGE] = function (Container $container) {
            return new ProductsCategoriesResourceRelationshipToProductCategoryStorageClientBridge(
                $container->getLocator()->productCategoryStorage()->client()
            );
        };

        return $container;
    }
}
