<?php

namespace SprykerFeature\Client\CategoryExporter;

use Generated\Client\Ide\FactoryAutoCompletion\CategoryExporter;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use SprykerFeature\Client\CategoryExporter\Model\Navigation;
use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

/**
 * @method CategoryExporter getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Navigation
     */
    public function createNavigation()
    {
        return $this->getFactory()->createModelNavigation(
            $this->getLocator()->kvStorage()->client(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return $this->getFactory()->createBuilderCategoryTreeBuilder(
            $this->getLocator()->kvStorage()->client(),
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
