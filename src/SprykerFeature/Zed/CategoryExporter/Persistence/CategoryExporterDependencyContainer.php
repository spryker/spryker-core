<?php

namespace SprykerFeature\Zed\CategoryExporter\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterPersistence;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\CategoryNodeQueryExpander;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\NavigationQueryExpander;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;

/**
 * Class CategoryExporterDependencyContainer
 * @package SprykerFeature\Zed\CategoryExporter\Persistence
 */
/**
 * @method CategoryExporterPersistence getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param $locale
     *
     * @return CategoryNodeQueryExpander
     */
    public function createCategoryNodeQueryExpander($locale)
    {
        return $this->getFactory()->createQueryExpanderCategoryNodeQueryExpander(
            $this->getCategoryQueryContainer(),
            $this->getLocaleIdentifier($locale)
        );
    }

    /**
     * @param $locale
     *
     * @return NavigationQueryExpander
     */
    public function createNavigationQueryExpander($locale)
    {
        return $this->getFactory()->createQueryExpanderNavigationQueryExpander(
            $this->getCategoryQueryContainer(),
            $this->getLocaleIdentifier($locale)
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
     * @param string $localeName
     * @return int
     */
    protected function getLocaleIdentifier($localeName)
    {
        return $this->getLocator()->locale()->facade()->getIdLocale($localeName);
    }
}
