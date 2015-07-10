<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\CategoryExporter\CategoryExporterDependencyProvider;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\CategoryNodeQueryExpander;
use SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander\NavigationQueryExpander;

/**
 * @method CategoryExporterPersistence getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractPersistenceDependencyContainer
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
            $locale
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
            $locale
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryExporterDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

}
