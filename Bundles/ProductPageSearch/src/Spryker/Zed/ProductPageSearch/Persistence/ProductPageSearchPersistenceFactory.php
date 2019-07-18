<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapper;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 */
class ProductPageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery
     */
    public function createProductAbstractPageSearch()
    {
        return SpyProductAbstractPageSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery
     */
    public function createProductConcretePageSearchQuery(): SpyProductConcretePageSearchQuery
    {
        return SpyProductConcretePageSearchQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface
     */
    public function createProductPageSearchMapper(): ProductPageSearchMapperInterface
    {
        return new ProductPageSearchMapper();
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageToPriceProductQueryContainerInterface
     */
    public function getPriceQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductImageQueryContainerInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @deprecated Use getCategoryQueryContainer() instead.
     *
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerInterface
     */
    public function getCategoryAttributeQuery()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodeQueryContainer(): SpyCategoryNodeQuery
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PROPEL_QUERY_CATEGORY_NODE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PROPEL_QUERY_PRODUCT);
    }
}
