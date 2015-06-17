<?php

namespace SprykerFeature\Client\CategoryExporter;

use SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\KeyBuilder\SharedResourceKeyBuilder;
use Generated\Client\Ide\FactoryAutoCompletion\CategoryExporter;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use SprykerFeature\Client\CategoryExporter\Model\Navigation;

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
     * @return KeyBuilderInterface
     */
    protected function createResourceKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderCategoryResourceKeyBuilder();
    }
}
