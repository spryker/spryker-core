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
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductCategoryFacadeBridge;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeBridge;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductPageSearchFacadeAdapter;

/**
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_PRODUCT_QUERY = 'PROPEL_PRODUCT_QUERY';

    /**
     * @var string
     */
    public const PROPEL_PRODUCT_CATEGORY_QUERY = 'PROPEL_PRODUCT_CATEGORY_QUERY';

    /**
     * @var string
     */
    public const PROPEL_PRODUCT_LIST_CATEGORY_QUERY = 'PROPEL_PRODUCT_LIST_CATEGORY_QUERY';

    /**
     * @var string
     */
    public const PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY = 'PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

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
        $container = $this->addProductPageSearchFacade($container);
        $container = $this->addProductListFacade($container);
        $container = $this->addProductCategoryFacade($container);

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
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductListSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPageSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_PAGE_SEARCH, function (Container $container) {
            return new ProductListSearchToProductPageSearchFacadeAdapter($container->getLocator()->productPageSearch()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LIST, function (Container $container) {
            return new ProductListSearchToProductListFacadeBridge($container->getLocator()->productList()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductListSearchToProductCategoryFacadeBridge($container->getLocator()->productCategory()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PRODUCT_QUERY, $container->factory(function () {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PRODUCT_CATEGORY_QUERY, $container->factory(function () {
            return SpyProductCategoryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListCategoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PRODUCT_LIST_CATEGORY_QUERY, $container->factory(function () {
            return SpyProductListCategoryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListProductConcretePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PRODUCT_LIST_PRODUCT_CONCRETE_QUERY, $container->factory(function () {
            return SpyProductListProductConcreteQuery::create();
        }));

        return $container;
    }
}
