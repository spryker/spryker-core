<?php

namespace SprykerFeature\Zed\Category\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryBusiness;
use SprykerFeature\Zed\Category\Business\Manager\NodeUrlManager;
use SprykerFeature\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use SprykerFeature\Zed\Category\Business\Model\CategoryWriterInterface;
use SprykerFeature\Zed\Category\Business\Renderer\CategoryTreeRenderer;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeReader;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeWriter;
use SprykerFeature\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use SprykerFeature\Zed\Category\Business\Tree\NodeWriterInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;

/**
 * @method CategoryBusiness getFactory()
 */
class CategoryDependencyContainer extends AbstractDependencyContainer
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
     * @return CategoryTreeReader
     */
    public function createCategoryTreeReader()
    {
        return $this->getFactory()->createTreeCategoryTreeReader(
            $this->createQueryContainer()
        );
    }

    /**
     * @return CategoryTreeRenderer
     */
    public function createCategoryTreeRenderer()
    {
        $locale = $this->createLocaleFacade()->getCurrentLocale();

        return $this->getFactory()->createRendererCategoryTreeRenderer(
            $this->createQueryContainer(),
            $locale
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function createQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }

    /**
     * @return CategoryWriterInterface
     */
    public function createCategoryWriter()
    {
        return $this->getFactory()->createModelCategoryWriter(
            $this->getLocator(),
            $this->createQueryContainer()
        );
    }

    /**
     * @return NodeWriterInterface
     */
    protected function createNodeWriter()
    {
        return $this->getFactory()->createTreeNodeWriter(
            $this->getLocator(),
            $this->createQueryContainer()
        );
    }

    /**
     * @return ClosureTableWriterInterface
     */
    protected function createClosureTableWriter()
    {
        return $this->getFactory()->createTreeClosureTableWriter(
            $this->getLocator(),
            $this->createQueryContainer()
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
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return CategoryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return CategoryToUrlInterface
     */
    protected function createUrlFacade()
    {
        return $this->getLocator()->url()->facade();
    }
}
