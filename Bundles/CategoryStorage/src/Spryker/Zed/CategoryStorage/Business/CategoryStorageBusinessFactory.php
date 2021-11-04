<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\CategoryStorage\Business\Deleter\Category\CategoryNodeStorageByCategoryEventsDeleter;
use Spryker\Zed\CategoryStorage\Business\Deleter\Category\CategoryNodeStorageByCategoryEventsDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsDeleter;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleter;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsDeleter;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractor;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapper;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapperInterface;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapper;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapperInterface;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilder;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\Category\CategoryNodeStorageByCategoryEventsWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\Category\CategoryNodeStorageByCategoryEventsWriterInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsWriterInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryStore\CategoryNodeStorageByCategoryStoreEventsWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryStore\CategoryNodeStorageByCategoryStoreEventsWriterInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsWriterInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryTreeStorageWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryTreeStorageWriterInterface;
use Spryker\Zed\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface getRepository()
 */
class CategoryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface
     */
    public function createCategoryNodeStorageWriter(): CategoryNodeStorageWriterInterface
    {
        return new CategoryNodeStorageWriter(
            $this->getEntityManager(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getCategoryFacade(),
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageDeleter(),
            $this->createCategoryNodeExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryTreeStorageWriterInterface
     */
    public function createCategoryTreeStorageWriter(): CategoryTreeStorageWriterInterface
    {
        return new CategoryTreeStorageWriter(
            $this->getEntityManager(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getCategoryFacade(),
            $this->createCategoryNodeExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    public function createCategoryStorageNodeTreeBuilder(): CategoryStorageNodeTreeBuilderInterface
    {
        return new CategoryStorageNodeTreeBuilder(
            $this->getStoreFacade(),
            $this->createCategoryNodeStorageMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface
     */
    public function createCategoryNodeStorageDeleter(): CategoryNodeStorageDeleterInterface
    {
        return new CategoryNodeStorageDeleter(
            $this->getEntityManager(),
            $this->getCategoryFacade(),
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapperInterface
     */
    public function createCategoryNodeStorageMapper(): CategoryNodeStorageMapperInterface
    {
        return new CategoryNodeStorageMapper($this->createCategoryLocalizedAttributesMapper());
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapperInterface
     */
    public function createCategoryLocalizedAttributesMapper(): CategoryLocalizedAttributesMapperInterface
    {
        return new CategoryLocalizedAttributesMapper();
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryStorageToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoryStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): CategoryStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\Category\CategoryNodeStorageByCategoryEventsWriterInterface
     */
    public function createCategoryNodeStorageByCategoryEventsWriter(): CategoryNodeStorageByCategoryEventsWriterInterface
    {
        return new CategoryNodeStorageByCategoryEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsWriterInterface
     */
    public function createCategoryNodeStorageByCategoryAttributeEventsWriter(): CategoryNodeStorageByCategoryAttributeEventsWriterInterface
    {
        return new CategoryNodeStorageByCategoryAttributeEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryStore\CategoryNodeStorageByCategoryStoreEventsWriterInterface
     */
    public function createCategoryNodeStorageByCategoryStoreEventsWriter(): CategoryNodeStorageByCategoryStoreEventsWriterInterface
    {
        return new CategoryNodeStorageByCategoryStoreEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsWriterInterface
     */
    public function createCategoryNodeStorageByCategoryTemplateEventsWriter(): CategoryNodeStorageByCategoryTemplateEventsWriterInterface
    {
        return new CategoryNodeStorageByCategoryTemplateEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Deleter\Category\CategoryNodeStorageByCategoryEventsDeleterInterface
     */
    public function createCategoryNodeStorageByCategoryEventsDeleter(): CategoryNodeStorageByCategoryEventsDeleterInterface
    {
        return new CategoryNodeStorageByCategoryEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageDeleter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryAttribute\CategoryNodeStorageByCategoryAttributeEventsDeleterInterface
     */
    public function createCategoryNodeStorageByCategoryAttributeEventsDeleter(): CategoryNodeStorageByCategoryAttributeEventsDeleterInterface
    {
        return new CategoryNodeStorageByCategoryAttributeEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageDeleter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryTemplate\CategoryNodeStorageByCategoryTemplateEventsDeleterInterface
     */
    public function createCategoryNodeStorageByCategoryTemplateEventsDeleter(): CategoryNodeStorageByCategoryTemplateEventsDeleterInterface
    {
        return new CategoryNodeStorageByCategoryTemplateEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeStorageDeleter(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface
     */
    public function createCategoryNodeExtractor(): CategoryNodeExtractorInterface
    {
        return new CategoryNodeExtractor();
    }
}
