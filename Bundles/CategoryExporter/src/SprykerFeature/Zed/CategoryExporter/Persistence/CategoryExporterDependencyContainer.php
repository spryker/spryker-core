<?php

namespace SprykerFeature\Zed\CategoryExporter\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\CategoryNodeQueryExpander;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\NavigationQueryExpander;

/**
 * @method CategoryExporterPersistence getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param LocaleTransfer $locale
     *
     * @return CategoryNodeQueryExpander
     */
    public function createCategoryNodeQueryExpander(LocaleTransfer $locale)
    {
        return $this->getFactory()->createQueryExpanderCategoryNodeQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale->getIdLocale()
        );
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return NavigationQueryExpander
     */
    public function createNavigationQueryExpander(LocaleTransfer $locale)
    {
        return $this->getFactory()->createQueryExpanderNavigationQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale->getIdLocale()
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
