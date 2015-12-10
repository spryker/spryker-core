<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\CategoryExporter\Service;

use SprykerFeature\Client\CategoryExporter\Service\KeyBuilder\CategoryResourceKeyBuilder;
use SprykerFeature\Client\CategoryExporter\Service\KeyBuilder\NavigationKeyBuilder as KeyBuilderNavigationKeyBuilder;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\CategoryExporter\Service\Builder\CategoryTreeBuilder;
use SprykerFeature\Client\CategoryExporter\Service\Model\Navigation;
use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CategoryExporterDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return Navigation
     */
    public function createNavigation()
    {
        return new Navigation(
            $this->getLocator()->storage()->client(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return new CategoryTreeBuilder(
            $this->getLocator()->storage()->client(),
            $this->createResourceKeyBuilder()
        );
    }

    /**
     * @return NavigationKeyBuilder
     */
    protected function createNavigationKeyBuilder()
    {
        return new KeyBuilderNavigationKeyBuilder();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function createResourceKeyBuilder()
    {
        return new CategoryResourceKeyBuilder();
    }

}
