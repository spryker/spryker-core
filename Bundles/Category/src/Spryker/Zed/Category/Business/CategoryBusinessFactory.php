<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\Business\Manager\NodeUrlManager;
use Spryker\Zed\Category\Business\Model\Category;
use Spryker\Zed\Category\Business\Model\Category\Category as CategoryEntityModel;
use Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttribute;
use Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParents;
use Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNode;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateReader;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSync;
use Spryker\Zed\Category\Business\Model\CategoryToucher;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTree;
use Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrl;
use Spryker\Zed\Category\Business\Model\CategoryWriter;
use Spryker\Zed\Category\Business\Renderer\CategoryTreeRenderer;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReader;
use Spryker\Zed\Category\Business\Tree\CategoryTreeWriter;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriter;
use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use Spryker\Zed\Category\Business\Tree\NodeWriter;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 */
class CategoryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @deprecated Will be removed with next major release
     *
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
        return new CategoryTreeRenderer(
            $this->getQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
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
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Category\Business\Model\CategoryWriterInterface
     */
    public function createCategoryWriter()
    {
        return new CategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\Category
     */
    public function createCategory()
    {
        return new Category(
            $this->createCategoryCategory(),
            $this->createCategoryNode(),
            $this->createCategoryAttribute(),
            $this->createCategoryUrl(),
            $this->createCategoryExtraParents(),
            $this->getQueryContainer(),
            $this->getRelationDeletePluginStack(),
            $this->getRelationUpdatePluginStack(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\Category\CategoryInterface
     */
    protected function createCategoryCategory()
    {
        return new CategoryEntityModel($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface|\Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeDeleterInterface
     */
    public function createCategoryNode()
    {
        return new CategoryNode(
            $this->createClosureTableWriter(),
            $this->getQueryContainer(),
            $this->createCategoryTransferGenerator(),
            $this->createCategoryToucher(),
            $this->createCategoryTree()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    public function createCategoryToucher()
    {
        return new CategoryToucher($this->getTouchFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface
     */
    protected function createCategoryTree()
    {
        return new CategoryTree(
            $this->getQueryContainer(),
            $this->createFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function createFacade()
    {
        return new CategoryFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface
     */
    protected function createCategoryAttribute()
    {
        return new CategoryAttribute($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface
     */
    protected function createCategoryUrl()
    {
        return new CategoryUrl(
            $this->getQueryContainer(),
            $this->getUrlFacade(),
            $this->createUrlPathGenerator(),
            $this->getCategoryUrlPathPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface
     */
    protected function createCategoryExtraParents()
    {
        return new CategoryExtraParents(
            $this->getQueryContainer(),
            $this->createClosureTableWriter(),
            $this->createCategoryToucher(),
            $this->createCategoryTree(),
            $this->createCategoryUrl(),
            $this->createCategoryTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    protected function getRelationDeletePluginStack()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_STACK_RELATION_DELETE);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected function getRelationUpdatePluginStack()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGIN_STACK_RELATION_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected function getCategoryUrlPathPlugins()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH);
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\NodeWriterInterface
     */
    public function createNodeWriter()
    {
        return new NodeWriter($this->getQueryContainer(), $this->createCategoryToucher());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    public function createClosureTableWriter()
    {
        return new ClosureTableWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Category\Business\Manager\NodeUrlManager
     */
    protected function createNodeUrlManager()
    {
        return new NodeUrlManager(
            $this->createCategoryTreeReader(),
            $this->createUrlPathGenerator(),
            $this->getUrlFacade(),
            $this->getQueryContainer()
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
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToEventInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_EVENT);
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

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface
     */
    public function createCategoryTemplateSync()
    {
        return new CategoryTemplateSync(
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateReaderInterface
     */
    public function createCategoryTemplateReader()
    {
        return new CategoryTemplateReader(
            $this->getQueryContainer()
        );
    }
}
