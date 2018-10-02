<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeBridge;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductPageSearchFacadeBridge;

class ProductListSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_PRODUCT_QUERY = 'PROPEL_PRODUCT_QUERY';
    public const PROPEL_PRODUCT_CATEGORY_QUERY = 'PROPEL_PRODUCT_CATEGORY_QUERY';
    public const PROPEL_PRODUCT_LIST_CATEGORY_QUERY = 'PROPEL_PRODUCT_LIST_CATEGORY_QUERY';
    public const PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY = 'PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductPageSearchFacade($container);
        $container = $this->addProductListFacade($container);

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
        $container = $this->addProductListCategoryPropelQuery($container);
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
            return new ProductListSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPageSearchFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_PAGE_SEARCH] = function (Container $container) {
            return new ProductListSearchToProductPageSearchFacadeBridge($container->getLocator()->productPageSearch()->facade());
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
            return new ProductListSearchToProductListFacadeBridge($container->getLocator()->productList()->facade());
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
    protected function addProductListCategoryPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_PRODUCT_LIST_CATEGORY_QUERY] = function () {
            return SpyProductListCategoryQuery::create();
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
        $container[static::PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY] = function () {
            return SpyProductListProductConcreteQuery::create();
        };

        return $container;
    }
}
