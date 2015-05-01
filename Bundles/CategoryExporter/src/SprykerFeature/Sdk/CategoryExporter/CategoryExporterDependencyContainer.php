<?php

namespace SprykerFeature\Sdk\CategoryExporter;

use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use \SprykerFeature\Zed\CategoryExporter\Business\KeyBuilder\ResourceKeyBuilder;
use Generated\Sdk\Ide\FactoryAutoCompletion\CategoryExporter;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\CategoryExporter\Builder\CategoryTreeBuilder;
use SprykerFeature\Sdk\CategoryExporter\Model\Navigation;

class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var CategoryExporter
     */
    protected $factory;

    /**
     * @return Navigation
     */
    public function createNavigation()
    {
        return $this->getFactory()->createModelNavigation(
            $this->getLocator()->kvStorage()->readClient()->getInstance(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return $this->getFactory()->createBuilderCategoryTreeBuilder(
            $this->getLocator()->kvStorage()->readClient()->getInstance(),
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
     * @return ResourceKeyBuilder
     */
    protected function createResourceKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderResourceKeyBuilder();
    }
}
