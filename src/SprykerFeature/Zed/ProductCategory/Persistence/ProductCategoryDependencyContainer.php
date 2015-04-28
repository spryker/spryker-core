<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryPersistence;
use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
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
}
