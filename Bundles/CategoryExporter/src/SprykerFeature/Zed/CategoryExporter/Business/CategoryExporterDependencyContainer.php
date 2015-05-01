<?php

namespace SprykerFeature\Zed\CategoryExporter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterBusiness;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Formatter\CategoryNodeFormatterInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method CategoryExporterBusiness getFactory()
 */
class CategoryExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Processor\CategoryNodeProcessor
     */
    public function createCategoryNodeProcessor()
    {
        return $this->getFactory()->createProcessorCategoryNodeProcessor(
            $this->getResourceKeyBuilder(),
            $this->getCategoryNodeFormatter()
        );
    }

    /**
     * @return Processor\NavigationProcessor
     */
    public function createNavigationProcessor()
    {
        return $this->getFactory()->createProcessorNavigationProcessor(
            $this->getNavigationKeyBuilder(),
            $this->getCategoryNodeFormatter()
        );
    }

    /**
     * @return Exploder\GroupedNodeExploder
     */
    public function createGroupedNodeExploder()
    {
        return $this->getFactory()->createExploderGroupedNodeExploder();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getNavigationKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderNavigationKeyBuilder();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getResourceKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderResourceKeyBuilder();
    }

    /**
     * @return CategoryNodeFormatterInterface
     */
    protected function getCategoryNodeFormatter()
    {
        return $this->getFactory()->createFormatterCategoryNodeFormatter(
            $this->createGroupedNodeExploder()
        );
    }
}
