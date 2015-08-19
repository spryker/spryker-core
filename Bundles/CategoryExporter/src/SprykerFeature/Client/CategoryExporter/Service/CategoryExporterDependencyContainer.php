<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\CategoryExporter\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CategoryExporter;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\CategoryExporter\Service\Builder\CategoryTreeBuilder;
use SprykerFeature\Client\CategoryExporter\Service\Model\Navigation;
use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

/**
 * @method CategoryExporter getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return Navigation
     */
    public function createNavigation()
    {
        return $this->getFactory()->createModelNavigation(
            $this->getLocator()->storage()->client(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return $this->getFactory()->createBuilderCategoryTreeBuilder(
            $this->getLocator()->storage()->client(),
            $this->createResourceKeyBuilder()
        );
    }

    /**
     * @return NavigationKeyBuilder
     */
    protected function createNavigationKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderNavigationKeyBuilder();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function createResourceKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderCategoryResourceKeyBuilder();
    }

}
