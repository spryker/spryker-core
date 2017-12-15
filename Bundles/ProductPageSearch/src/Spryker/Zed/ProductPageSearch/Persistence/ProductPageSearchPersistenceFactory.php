<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer getQueryContainer()
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
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageToPriceQueryContainerInterface
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
     * @return ProductPageSearchToCategoryQueryContainerInterface
     */
    public function getCategoryAttributeQuery()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

}
