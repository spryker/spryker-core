<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business;

use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGenerator;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriter;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToEventFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 */
class CmsBlockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockReaderInterface
     */
    public function createCmsBlockReader()
    {
        return new CmsBlockReader(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface
     */
    public function createCmsBlockMapper()
    {
        return new CmsBlockMapper(
            $this->createCmsBlockStoreRelationMapper()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriterInterface
     */
    public function createCmsBlockWrite()
    {
        return new CmsBlockWriter(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper(),
            $this->createCmsBlockGlossaryWriter(),
            $this->createCmsBlockStoreRelationWriter(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH),
            $this->createCmsBlockTemplateManager(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::PLUGIN_CMS_BLOCK_UPDATE)
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToEventFacadeInterface
     */
    public function getEventFacade(): CmsBlockToEventFacadeInterface
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManagerInterface
     */
    public function createCmsBlockTemplateManager()
    {
        return new CmsBlockTemplateManager(
            $this->getQueryContainer(),
            $this->createCmsBlockTemplateMapper(),
            $this->getConfig(),
            $this->createFinder()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManagerInterface
     */
    public function createCmsBlockGlossaryManager()
    {
        return new CmsBlockGlossaryManager(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_LOCALE)
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriterInterface
     */
    public function createCmsBlockGlossaryWriter()
    {
        return new CmsBlockGlossaryWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY),
            $this->createCmsBlockGlossaryKeyGenerator(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::QUERY_CONTAINER_GLOSSARY),
            $this->getTouchFacade(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationWriterInterface
     */
    public function createCmsBlockStoreRelationWriter()
    {
        return new CmsBlockStoreRelationWriter(
            $this->getQueryContainer(),
            $this->createCmsBlockStoreRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface
     */
    public function createCmsBlockStoreRelationReader()
    {
        return new CmsBlockStoreRelationReader(
            $this->getQueryContainer(),
            $this->createCmsBlockStoreRelationMapper()
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
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface
     */
    protected function createCmsBlockTemplateMapper()
    {
        return new CmsBlockTemplateMapper();
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface
     */
    protected function createCmsBlockGlossaryKeyGenerator()
    {
        return new CmsBlockGlossaryKeyGenerator(
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY)
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface
     */
    protected function createCmsBlockStoreRelationMapper()
    {
        return new CmsBlockStoreRelationMapper();
    }
}
