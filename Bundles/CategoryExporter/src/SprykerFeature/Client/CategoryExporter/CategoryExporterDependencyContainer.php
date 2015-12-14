<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\CategoryExporter;

use SprykerFeature\Client\CategoryExporter\KeyBuilder\CategoryResourceKeyBuilder;
use SprykerFeature\Client\CategoryExporter\KeyBuilder\NavigationKeyBuilder as KeyBuilderNavigationKeyBuilder;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use SprykerFeature\Client\CategoryExporter\Model\Navigation;
use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CategoryExporterDependencyContainer extends AbstractDependencyContainer
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
