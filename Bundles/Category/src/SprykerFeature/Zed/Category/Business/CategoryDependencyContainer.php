<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business;

use SprykerFeature\Zed\Category\Business\Generator\UrlPathGenerator;
use SprykerFeature\Zed\Category\Business\Tree\ClosureTableWriter;
use SprykerFeature\Zed\Category\Business\Tree\NodeWriter;
use SprykerFeature\Zed\Category\Business\Model\CategoryWriter;
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
        return new CategoryTreeWriter(
            $this->createNodeWriter(),
            $this->createClosureTableWriter(),
            $this->createCategoryTreeReader(),
            $this->createNodeUrlManager(),
            $this->createTouchFacade(),
            $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @param array $category
     *
     * @return CategoryTreeFormatter
     */
    public function createCategoryTreeStructure(array $category)
    {
        return new CategoryTreeFormatter($category);
    }

    /**
     * @return CategoryTreeReader
     */
    public function createCategoryTreeReader()
    {
        return new CategoryTreeReader(
            $this->getQueryContainer(),
            $this->createCategoryTreeFormatter()
        );
    }

    /**
     * @return CategoryTreeRenderer
     */
    public function createCategoryTreeRenderer()
    {
        $locale = $this->createLocaleFacade()->getCurrentLocale();

        return new CategoryTreeRenderer(
            $this->getQueryContainer(),
            $locale
        );
    }

    /**
     * @return CategoryWriterInterface
     */
    public function createCategoryWriter()
    {
        return new CategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return NodeWriterInterface
     */
    public function createNodeWriter()
    {
        return new NodeWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ClosureTableWriterInterface
     */
    protected function createClosureTableWriter()
    {
        return new ClosureTableWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return NodeUrlManager
     */
    protected function createNodeUrlManager()
    {
        return new NodeUrlManager(
            $this->createCategoryTreeReader(),
            $this->createUrlPathGenerator(),
            $this->createUrlFacade()
        );
    }

    /**
     * @return UrlPathGeneratorInterface
     */
    public function createUrlPathGenerator()
    {
        return new UrlPathGenerator();
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

    /**
     * @return CategoryTreeFormatter
     */
    protected function createCategoryTreeFormatter()
    {
        return new CategoryTreeFormatter();
    }

    /**
     * @return TransferGeneratorInterface
     */
    public function createCategoryTransferGenerator()
    {
        return new TransferGenerator();
    }

}
