<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\ProductCategory\Persistence\QueryExpander\ProductCategoryPathQueryExpander;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;

/**
 * @method ProductCategoryConfig getConfig()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @param LocaleTransfer $locale
     *
     * @return ProductCategoryPathQueryExpander
     */
    public function createProductCategoryPathQueryExpander(LocaleTransfer $locale)
    {
        return new ProductCategoryPathQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @return SpyProductCategoryQuery
     */
    public function createProductCategoryQuery()
    {
        return SpyProductCategoryQuery::create();
    }

}
