<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryPersistence;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;
use SprykerFeature\Zed\ProductCategory\Persistence\QueryExpander\ProductCategoryPathQueryExpander;

/**
 * @method ProductCategoryPersistence getFactory()
 */
class ProductCategoryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param LocaleDto $locale
     *
     * @return ProductCategoryPathQueryExpander
     */
    public function createProductCategoryPathQueryExpander(LocaleDto $locale)
    {
        return $this->getFactory()->createQueryExpanderProductCategoryPathQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }

    /**
     * @return SpyProductCategoryQuery
     */
    public function createProductCategoryQuery()
    {
        return SpyProductCategoryQuery::create();
    }
}
