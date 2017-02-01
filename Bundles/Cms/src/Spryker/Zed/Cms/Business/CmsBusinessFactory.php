<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business;

use Spryker\Zed\Cms\Business\Block\BlockManager;
use Spryker\Zed\Cms\Business\Block\BlockRemover;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossaryReader;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaver;
use Spryker\Zed\Cms\Business\Mapping\GlossaryKeyMappingManager;
use Spryker\Zed\Cms\Business\Page\CmsPageActivator;
use Spryker\Zed\Cms\Business\Page\CmsPageReader;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilder;
use Spryker\Zed\Cms\Business\Page\PageManager;
use Spryker\Zed\Cms\Business\Page\PageRemover;
use Spryker\Zed\Cms\Business\Page\CmsPageSaver;
use Spryker\Zed\Cms\Business\Template\TemplateManager;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainer getQueryContainer()
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

    /**
     * @return \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Page\PageRemoverInterface
     */
    public function createPageRemover()
    {
        return new PageRemover(
            $this->getQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Block\BlockRemoverInterface
     */
    public function createBlockRemover()
    {
        return new BlockRemover(
            $this->getQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Page\CmsPageSaverInterface
     */
    public function createCmsPageSaver()
    {
        return new CmsPageSaver(
            $this->getUrlFacade(),
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->createCmsUrlBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Page\CmsPageReaderInterface
     */
    public function createCmsPageReader()
    {
        return new CmsPageReader($this->getQueryContainer(), $this->createCmsUrlBuilder());
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Mapping\CmsGlossaryReaderInterface
     */
    public function createCmsGlossaryReader()
    {
        return new CmsGlossaryReader($this->getQueryContainer(), $this->getLocaleFacade(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface
     */
    public function createCmsGlossarySaver()
    {
        return new CmsGlossarySaver($this->getQueryContainer(), $this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Page\CmsPageActivatorInterface
     */
    public function createCmsPageActivator()
    {
        return new CmsPageActivator($this->getQueryContainer(), $this->getTouchFacade(), $this->getUrlFacade());
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    public function createCmsUrlBuilder()
    {
        return new CmsPageUrlBuilder();
    }
}
