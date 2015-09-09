<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business;

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

/**
 * @method CmsBusiness getFactory()
 */
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
        return $this->getFactory()->createPagePageManager(
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
        return $this->getFactory()->createTemplateTemplateManager(
            $this->getCmsQueryContainer(),
            $this->getLocator(),
            $this->getConfig()
        );
    }

    /**
     * @return BlockManagerInterface
     */
    public function getBlockManager()
    {
        return $this->getFactory()->createBlockBlockManager(
            $this->getCmsQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return GlossaryKeyMappingManagerInterface
     */
    public function getGlossaryKeyMappingManager()
    {
        return $this->getFactory()->createMappingGlossaryKeyMappingManager(
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
}
