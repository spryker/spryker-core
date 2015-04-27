<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryPersistence;
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
     * @param string $locale
     *
     * @return ProductCategoryPathQueryExpander
     */
    public function createProductCategoryPathQueryExpander($locale)
    {
        return $this->getFactory()->createQueryExpanderProductCategoryPathQueryExpander(
            $this->getCategoryQueryContainer(),
            $this->getLocaleIdentifier($locale)
        );
    }

    /**
     * @param string $locale
     *
     * @return int
     */
    protected function getLocaleIdentifier($locale)
    {
        $localeId = $this->getLocator()->locale()->facade()->getLocale($locale)->getIdLocale();

        return $localeId;
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }
}
