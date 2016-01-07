<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business;

use Spryker\Zed\Cms\Business\Mapping\GlossaryKeyMappingManager;
use Spryker\Zed\Cms\Business\Block\BlockManager;
use Spryker\Zed\Cms\Business\Template\TemplateManager;
use Spryker\Zed\Cms\Business\Page\PageManager;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Cms\Business\Block\BlockManagerInterface;
use Spryker\Zed\Cms\Business\Mapping\GlossaryKeyMappingManagerInterface;
use Spryker\Zed\Cms\Business\Page\PageManagerInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Symfony\Component\Finder\Finder;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * @method CmsConfig getConfig()
 * @method CmsQueryContainer getQueryContainer()
 */
class CmsBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsQueryContainerInterface
     */
    protected function getCmsQueryContainer()
    {
        return $this->getQueryContainer();
    }

    /**
     * @return PageManagerInterface
     */
    public function createPageManager()
    {
        return new PageManager(
            $this->getCmsQueryContainer(),
            $this->createTemplateManager(),
            $this->createBlockManager(),
            $this->getGlossaryFacade(),
            $this->getTouchFacade(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return TemplateManagerInterface
     */
    public function createTemplateManager()
    {
        return new TemplateManager(
            $this->getCmsQueryContainer(),
            $this->getConfig(),
            $this->createFinder()
        );
    }

    /**
     * @return BlockManagerInterface
     */
    public function createBlockManager()
    {
        return new BlockManager(
            $this->getCmsQueryContainer(),
            $this->getTouchFacade(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return GlossaryKeyMappingManagerInterface
     */
    public function createGlossaryKeyMappingManager()
    {
        return new GlossaryKeyMappingManager(
            $this->getGlossaryFacade(),
            $this->getCmsQueryContainer(),
            $this->createTemplateManager(),
            $this->createPageManager(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return Finder
     */
    protected function createFinder()
    {
        return new Finder();
    }

    /**
     * @return CmsToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return CmsToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return CmsToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

}
