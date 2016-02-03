<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Business;

use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriter;
use Spryker\Zed\Category\Business\Tree\NodeWriter;
use Spryker\Zed\Category\Business\Model\CategoryWriter;
use Spryker\Zed\Category\Business\Manager\NodeUrlManager;
use Spryker\Zed\Category\Business\Renderer\CategoryTreeRenderer;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReader;
use Spryker\Zed\Category\Business\Tree\CategoryTreeWriter;
use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Tool\GraphPhpDocumentor\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Tool\Graph\Graph;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 */
class CategoryBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Category\Business\Tree\CategoryTreeWriter
     */
    public function createCategoryTreeWriter()
    {
        return new CategoryTreeWriter(
            $this->createNodeWriter(),
            $this->createClosureTableWriter(),
            $this->createCategoryTreeReader(),
            $this->createNodeUrlManager(),
            $this->getTouchFacade(),
            $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @param array $category
     *
     * @return \Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter
     */
    public function createCategoryTreeStructure(array $category)
    {
        return new CategoryTreeFormatter($category);
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\CategoryTreeReader
     */
    public function createCategoryTreeReader()
    {
        return new CategoryTreeReader(
            $this->getQueryContainer(),
            $this->createCategoryTreeFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Renderer\CategoryTreeRenderer
     */
    public function createCategoryTreeRenderer()
    {
        $locale = $this->getLocaleFacade()->getCurrentLocale();

        return new CategoryTreeRenderer(
            $this->getQueryContainer(),
            $locale,
            $this->createGraphViz()
        );
    }

    /**
     * @return GraphViz
     */
    protected function createGraphViz()
    {
        $adapter = $this->createGraphAdapter();

        return new Graph($adapter, 'Category Tree');
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryWriterInterface
     */
    public function createCategoryWriter()
    {
        return new CategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\NodeWriterInterface
     */
    public function createNodeWriter()
    {
        return new NodeWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    protected function createClosureTableWriter()
    {
        return new ClosureTableWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Manager\NodeUrlManager
     */
    protected function createNodeUrlManager()
    {
        return new NodeUrlManager(
            $this->createCategoryTreeReader(),
            $this->createUrlPathGenerator(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    public function createUrlPathGenerator()
    {
        return new UrlPathGenerator();
    }

    /**
     * @deprecated Use getTouchFacade() instead.
     *
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected function createTouchFacade()
    {
        trigger_error('Deprecated, use getTouchFacade() instead.', E_USER_DEPRECATED);

        return $this->getTouchFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @deprecated Use getUrlFacade() instead.
     *
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected function createUrlFacade()
    {
        trigger_error('Deprecated, use getUrlFacade() instead.', E_USER_DEPRECATED);

        return $this->getUrlFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter
     */
    protected function createCategoryTreeFormatter()
    {
        return new CategoryTreeFormatter();
    }

    /**
     * @return \Spryker\Zed\Category\Business\TransferGeneratorInterface
     */
    public function createCategoryTransferGenerator()
    {
        return new TransferGenerator();
    }

    /**
     * @return \Spryker\Tool\GraphPhpDocumentor\Adapter\PhpDocumentorGraphAdapter
     */
    protected function createGraphAdapter()
    {
        return new PhpDocumentorGraphAdapter();
    }

}
