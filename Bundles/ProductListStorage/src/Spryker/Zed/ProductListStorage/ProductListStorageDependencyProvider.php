<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToEventBehaviourFacadeBridge;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeBridge;

class ProductListStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_PRODUCT_QUERY = 'PROPEL_PRODUCT_QUERY';
    public const PROPEL_PRODUCT_CATEGORY_QUERY = 'PROPEL_PRODUCT_CATEGORY_QUERY';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';
    public const PROPEL_QUERY_PRODUCT_LIST_PRODUCT_CONCRETE = 'PROPEL_QUERY_PRODUCT_LIST_PRODUCT_CONCRETE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductListFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductCategoryPropelQuery($container);
        $container = $this->addProductListProductConcretePropelQuery($container);

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
            return new ProductListStorageToEventBehaviourFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_LIST] = function (Container $container) {
            return new ProductListStorageToProductListFacadeBridge($container->getLocator()->productList()->facade());
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
        $container[static::PROPEL_PRODUCT_QUERY] = function () {
            return SpyProductQuery::create();
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
        $container[static::PROPEL_PRODUCT_CATEGORY_QUERY] = function () {
            return SpyProductCategoryQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListProductConcretePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_LIST_PRODUCT_CONCRETE] = function () {
            return SpyProductListProductConcreteQuery::create();
        };

        return $container;
    }
}
