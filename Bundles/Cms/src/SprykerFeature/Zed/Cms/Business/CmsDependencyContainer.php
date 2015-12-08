<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business;

use SprykerFeature\Zed\Cms\Business\Mapping\GlossaryKeyMappingManager;
use SprykerFeature\Zed\Cms\Business\Block\BlockManager;
use SprykerFeature\Zed\Cms\Business\Template\TemplateManager;
use SprykerFeature\Zed\Cms\Business\Page\PageManager;
use Generated\Zed\Ide\FactoryAutoCompletion\CmsBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Cms\Business\Block\BlockManagerInterface;
use SprykerFeature\Zed\Cms\Business\Mapping\GlossaryKeyMappingManagerInterface;
use SprykerFeature\Zed\Cms\Business\Page\PageManagerInterface;
use SprykerFeature\Zed\Cms\Business\Template\TemplateManagerInterface;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use sprykerfeature\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Symfony\Component\Finder\Finder;

class CmsDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CmsQueryContainerInterface
     */
    protected function getCmsQueryContainer()
    {
        return $this->getLocator()->cms()->queryContainer();
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
            $this->getUrlFacade(),
            $this->getLocator()
        );
    }

    /**
     * @return TemplateManagerInterface
     */
    public function getTemplateManager()
    {
        return new TemplateManager(
            $this->getCmsQueryContainer(),
            $this->getLocator(),
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
            $this->getLocator(),
            $this->getProvidedDependency(CmsDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return CmsToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getLocator()->glossary()->facade();
    }

    /**
     * @return CmsToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return CmsToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getLocator()->url()->facade();
    }

    /**
     * @return Finder
     */
    protected function getFinder()
    {
        return new Finder();
    }

}
