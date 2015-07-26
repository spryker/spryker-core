<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryExporterBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Exploder\GroupedNodeExploder;
use SprykerFeature\Zed\CategoryExporter\Business\Formatter\CategoryNodeFormatterInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Processor\CategoryNodeProcessor;
use SprykerFeature\Zed\CategoryExporter\Business\Processor\NavigationProcessor;
use SprykerFeature\Zed\CategoryExporter\Persistence\CategoryExporterQueryContainer;

/**
 * @method CategoryExporterBusiness getFactory()
 * @method CategoryExporterQueryContainer getQueryContainer()
 */
class CategoryExporterDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CategoryNodeProcessor
     */
    public function createCategoryNodeProcessor()
    {
        return $this->getFactory()->createProcessorCategoryNodeProcessor(
            $this->createResourceKeyBuilder(),
            $this->createCategoryNodeFormatter()
        );
    }

    /**
     * @return NavigationProcessor
     */
    public function createNavigationProcessor()
    {
        return $this->getFactory()->createProcessorNavigationProcessor(
            $this->createNavigationKeyBuilder(),
            $this->createCategoryNodeFormatter()
        );
    }

    /**
     * @return GroupedNodeExploder
     */
    public function createGroupedNodeExploder()
    {
        return $this->getFactory()->createExploderGroupedNodeExploder();
    }

    /**
     * @return KeyBuilderInterface
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

    /**
     * @return CategoryNodeFormatterInterface
     */
    protected function createCategoryNodeFormatter()
    {
        return $this->getFactory()->createFormatterCategoryNodeFormatter(
            $this->createGroupedNodeExploder()
        );
    }

    /**
     * @return CategoryExporterQueryContainer
     */
    public function createQueryExpander()
    {
        return $this->getQueryContainer();
    }

}
