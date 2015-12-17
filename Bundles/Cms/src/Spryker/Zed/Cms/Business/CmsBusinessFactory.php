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

/**
 * @method CmsConfig getConfig()
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
    public function getPageManager()
    {
        return new PageManager(
            $this->getCmsQueryContainer(),
            $this->getTemplateManager(),
            $this->getBlockManager(),
            $this->getGlossaryFacade(),
            $this->getTouchFacade(),
            $this->getUrlFacade()
        );
    }

    /**
     * @return TemplateManagerInterface
     */
    public function getTemplateManager()
    {
        return new TemplateManager(
            $this->getCmsQueryContainer(),
            $this->getConfig(),
            $this->getFinder()
        );
    }

    /**
     * @return BlockManagerInterface
     */
    public function getBlockManager()
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
    public function getGlossaryKeyMappingManager()
    {
        return new GlossaryKeyMappingManager(
            $this->getGlossaryFacade(),
            $this->getCmsQueryContainer(),
            $this->getTemplateManager(),
            $this->getPageManager(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
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

    /**
     * @return Finder
     */
    protected function getFinder()
    {
        return new Finder();
    }

}
