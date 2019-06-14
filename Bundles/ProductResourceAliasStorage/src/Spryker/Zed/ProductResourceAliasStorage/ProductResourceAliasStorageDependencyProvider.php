<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductResourceAliasStorage\Dependency\Facade\ProductResourceAliasStorageToEventBehaviorFacadeBridge;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageConfig getConfig()
 */
class ProductResourceAliasStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT_STORAGE = 'PROPEL_QUERY_PRODUCT_ABSTRACT_STORAGE';
    public const PROPEL_QUERY_PRODUCT_CONCRETE_STORAGE = 'PROPEL_QUERY_PRODUCT_CONCRETE_STORAGE';

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
        $this->addProductAbstractPropelQuery($container);
        $this->addProductPropelQuery($container);
        $this->addProductAbstractStoragePropelQuery($container);
        $this->addProductConcreteStoragePropelQuery($container);

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
            return new ProductResourceAliasStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT] = function () {
            return SpyProductQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT] = function () {
            return SpyProductAbstractQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractStoragePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT_STORAGE] = function () {
            return SpyProductAbstractStorageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteStoragePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_CONCRETE_STORAGE] = function () {
            return SpyProductConcreteStorageQuery::create();
        };

        return $container;
    }
}
