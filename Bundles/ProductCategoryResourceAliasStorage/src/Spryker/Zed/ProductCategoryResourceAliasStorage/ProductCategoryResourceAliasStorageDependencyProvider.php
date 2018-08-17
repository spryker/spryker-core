<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Dependency\Facade\ProductCategoryResourceAliasStorageToEventBehaviorFacadeBridge;

class ProductCategoryResourceAliasStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT_CATEGORY_STORAGE = 'PROPEL_QUERY_PRODUCT_ABSTRACT_CATEGORY_STORAGE';
    public const PROPEL_QUERY_PRODUCT_CATEGORY = 'PROPEL_QUERY_PRODUCT_CATEGORY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $this->addProductAbstractCategoryStoragePropelQuery($container);
        $this->addProductCategoryPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductCategoryResourceAliasStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractCategoryStoragePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT_CATEGORY_STORAGE] = function () {
            return SpyProductAbstractCategoryStorageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_CATEGORY] = function () {
            return SpyProductCategoryQuery::create();
        };
        return $container;
    }
}
