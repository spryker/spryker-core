<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductResourceAliasStorage\Dependency\Facade\PriceProductResourceAliasStorageToEventBehaviorFacadeBridge;

class PriceProductResourceAliasStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_PRICE_PRODUCT_ABSTRACT_STORAGE = 'PROPEL_QUERY_PRICE_PRODUCT_ABSTRACT_STORAGE';
    public const PROPEL_QUERY_PRICE_PRODUCT_CONCRETE_STORAGE = 'PROPEL_QUERY_PRICE_PRODUCT_CONCRETE_STORAGE';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';
    public const PROPEL_QUERY_PRICE_PRODUCT_STORE = 'PROPEL_QUERY_PRICE_PRODUCT_STORE';

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
        $container = $this->addPriceProductAbstractPropelQuery($container);
        $container = $this->addPriceProductConcretePropelQuery($container);
        $container = $this->addProductAbstractPropelQuery($container);
        $container = $this->addProductConcretePropelQuery($container);
        $container = $this->addPriceProductStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductAbstractPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_PRODUCT_ABSTRACT_STORAGE] = function () {
            return SpyPriceProductAbstractStorageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductConcretePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_PRODUCT_CONCRETE_STORAGE] = function () {
            return SpyPriceProductConcreteStorageQuery::create();
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
    protected function addProductConcretePropelQuery(Container $container): Container
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
    protected function addPriceProductStorePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_PRODUCT_STORE] = function () {
            return SpyPriceProductStoreQuery::create();
        };

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
            return new PriceProductResourceAliasStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }
}
