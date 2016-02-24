<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\Business\Manager\NodeUrlManager;
use Spryker\Zed\Category\Business\Model\CategoryWriter;
use Spryker\Zed\Category\Business\Renderer\CategoryTreeRenderer;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReader;
use Spryker\Zed\Category\Business\Tree\CategoryTreeWriter;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriter;
use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use Spryker\Zed\Category\Business\Tree\NodeWriter;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
            $this->getQueryContainer()->getConnection()
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
            $this->getGraph()->init('Category Tree')
        );
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraph()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_GRAPH);
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
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE);
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

}
