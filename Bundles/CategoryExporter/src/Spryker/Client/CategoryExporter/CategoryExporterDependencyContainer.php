<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\CategoryExporter;

use Spryker\Client\CategoryExporter\KeyBuilder\CategoryResourceKeyBuilder;
use Spryker\Client\CategoryExporter\KeyBuilder\NavigationKeyBuilder as KeyBuilderNavigationKeyBuilder;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use Spryker\Client\CategoryExporter\Model\Navigation;
use Spryker\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CategoryExporterDependencyContainer extends AbstractFactory
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
