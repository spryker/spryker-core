<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryBusiness;
use SprykerFeature\Zed\Category\Business\Manager\NodeUrlManager;
use SprykerFeature\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use SprykerFeature\Zed\Category\Business\Model\CategoryWriterInterface;
use SprykerFeature\Zed\Category\Business\Renderer\CategoryTreeRenderer;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeReader;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeWriter;
use SprykerFeature\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use SprykerFeature\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use SprykerFeature\Zed\Category\Business\Tree\NodeWriterInterface;
use SprykerFeature\Zed\Category\CategoryDependencyProvider;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;

/**
 * @method CategoryBusiness getFactory()
 * @method CategoryQueryContainer getQueryContainer()
 */
class CategoryDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CategoryTreeWriter
     */
    public function createCategoryTreeWriter()
    {
        return $this->getFactory()->createTreeCategoryTreeWriter(
            $this->createCategoryWriter(),
            $this->createNodeWriter(),
            $this->createClosureTableWriter(),
            $this->createCategoryTreeReader(),
            $this->createNodeUrlManager(),
            $this->createTouchFacade()
        );
    }

    /**
     * @param array $category
     *
     * @return CategoryTreeFormatter
     */
    public function createCategoryTreeStructure(array $category)
    {
        return $this->getFactory()->createTreeFormatterCategoryTreeFormatter($category);
    }

    /**
     * @return CategoryTreeReader
     */
    public function createCategoryTreeReader()
    {
        return $this->getFactory()->createTreeCategoryTreeReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return CategoryTreeRenderer
     */
    public function createCategoryTreeRenderer()
    {
        $locale = $this->createLocaleFacade()->getCurrentLocale();

        return $this->getFactory()->createRendererCategoryTreeRenderer(
            $this->getQueryContainer(),
            $locale
        );
    }

    /**
     * @return CategoryWriterInterface
     */
    public function createCategoryWriter()
    {
        return $this->getFactory()->createModelCategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return NodeWriterInterface
     */
    public function createNodeWriter()
    {
        return $this->getFactory()->createTreeNodeWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ClosureTableWriterInterface
     */
    protected function createClosureTableWriter()
    {
        return $this->getFactory()->createTreeClosureTableWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return NodeUrlManager
     */
    protected function createNodeUrlManager()
    {
        return $this->getFactory()->createManagerNodeUrlManager(
            $this->createCategoryTreeReader(),
            $this->createUrlPathGenerator(),
            $this->createUrlFacade()
        );
    }

    /**
     * @return UrlPathGeneratorInterface
     */
    protected function createUrlPathGenerator()
    {
        return $this->getFactory()->createGeneratorUrlPathGenerator();
    }

    /**
     * @return CategoryToTouchInterface
     */
    protected function createTouchFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return CategoryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return CategoryToUrlInterface
     */
    protected function createUrlFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_URL);
    }

}
