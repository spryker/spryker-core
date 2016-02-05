<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\ProductCategory\Persistence\QueryExpander\ProductCategoryPathQueryExpander;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Spryker\Zed\ProductCategory\Persistence\QueryExpander\ProductCategoryPathQueryExpander
     */
    public function createProductCategoryPathQueryExpander(LocaleTransfer $locale)
    {
        return new ProductCategoryPathQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale
        );
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function createProductCategoryQuery()
    {
        return SpyProductCategoryQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductQuery()
    {
        return SpyProductQuery::create();
    }

}
