<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientBridge;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CategoriesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';
    public const CLIENT_PRODUCT_CATEGORY_RESOURCE_ALIAS_STORAGE = 'CLIENT_PRODUCT_CATEGORY_RESOURCE_ALIAS_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->getCategoryStorageClient($container);
        $container = $this->getProductCategoryResourceAliasStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function getCategoryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_CATEGORY_STORAGE] = function () use ($container) {
            return new CategoriesRestApiToCategoryStorageClientBridge(
                $container->getLocator()->categoryStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function getProductCategoryResourceAliasStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_CATEGORY_RESOURCE_ALIAS_STORAGE] = function () use ($container) {
            return new CategoriesRestApiToProductCategoryResourceAliasStorageClientBridge(
                $container->getLocator()->productCategoryResourceAliasStorage()->client()
            );
        };

        return $container;
    }
}
