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
use Spryker\Zed\Cms\CmsDependencyProvider;
use Symfony\Component\Finder\Finder;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * @method CmsConfig getConfig()
 * @method CmsQueryContainer getQueryContainer()
 */
class CmsBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Cms\Business\Page\PageManagerInterface
     */
    public function createPageManager()
    {
        return new PageManager(
            $this->getQueryContainer(),
            $this->createTemplateManager(),
            $this->createBlockManager(),
            $this->getGlossaryFacade(),
            $this->getTouchFacade(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    public function createTemplateManager()
    {
        return new TemplateManager(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Block\BlockManagerInterface
     */
    public function createBlockManager()
    {
        return new BlockManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Mapping\GlossaryKeyMappingManagerInterface
     */
    public function createGlossaryKeyMappingManager()
    {
        return new GlossaryKeyMappingManager(
            $this->getGlossaryFacade(),
            $this->getQueryContainer(),
            $this->createTemplateManager(),
            $this->createPageManager(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder()
    {
        return new Finder();
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_URL);
    }

}
