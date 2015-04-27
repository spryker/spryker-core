<?php

namespace SprykerFeature\Zed\CategoryExporter\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterPersistence;
use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\CategoryNodeQueryExpander;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\NavigationQueryExpander;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;

/**
 * @method CategoryExporterPersistence getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param LocaleDto $locale
     *
     * @return CategoryNodeQueryExpander
     */
    public function createCategoryNodeQueryExpander(LocaleDto $locale)
    {
        return $this->getFactory()->createQueryExpanderCategoryNodeQueryExpander(
            $this->getCategoryQueryContainer(),
            $locale->getIdLocale()
        );
    }

    /**
     * @param LocaleDto $locale
     *
     * @return NavigationQueryExpander
     */
    public function createNavigationQueryExpander(LocaleDto $locale)
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
