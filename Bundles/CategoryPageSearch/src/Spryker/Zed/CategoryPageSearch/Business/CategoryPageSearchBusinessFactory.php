<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

use Spryker\Zed\CategoryPageSearch\Business\Deleter\Category\CategoryNodePageSearchByCategoryEventsDeleter;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\Category\CategoryNodePageSearchByCategoryEventsDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsDeleter;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleter;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsDeleter;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractor;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractorInterface;
use Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapper;
use Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapper;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\Category\CategoryNodePageSearchByCategoryEventsWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\Category\CategoryNodePageSearchByCategoryEventsWriterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsWriterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryStore\CategoryNodePageSearchByCategoryStoreEventsWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryStore\CategoryNodePageSearchByCategoryStoreEventsWriterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsWriterInterface;
use Spryker\Zed\CategoryPageSearch\CategoryPageSearchDependencyProvider;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchRepositoryInterface getRepository()
 */
class CategoryPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface
     */
    public function createCategoryNodePageSearchWriter(): CategoryNodePageSearchWriterInterface
    {
        return new CategoryNodePageSearchWriter(
            $this->getEntityManager(),
            $this->createCategoryNodePageSearchMapper(),
            $this->getCategoryFacade(),
            $this->getStoreFacade(),
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchDeleter(),
            $this->createCategoryNodeExtractor()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface
     */
    public function createCategoryNodePageSearchMapper(): CategoryNodePageSearchMapperInterface
    {
        return new CategoryNodePageSearchMapper($this->createCategoryNodePageSearchDataMapper());
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface
     */
    public function createCategoryNodePageSearchDataMapper(): CategoryNodePageSearchDataMapperInterface
    {
        return new CategoryNodePageSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface
     */
    public function createCategoryNodePageSearchDeleter(): CategoryNodePageSearchDeleterInterface
    {
        return new CategoryNodePageSearchDeleter(
            $this->getEntityManager(),
            $this->getCategoryFacade(),
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodeExtractor()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoryPageSearchToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryPageSearchToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): CategoryPageSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsWriterInterface
     */
    public function createCategoryNodePageSearchByCategoryAttributeEventsWriter(): CategoryNodePageSearchByCategoryAttributeEventsWriterInterface
    {
        return new CategoryNodePageSearchByCategoryAttributeEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Writer\Category\CategoryNodePageSearchByCategoryEventsWriterInterface
     */
    public function createCategoryNodePageSearchByCategoryEventsWriter(): CategoryNodePageSearchByCategoryEventsWriterInterface
    {
        return new CategoryNodePageSearchByCategoryEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryStore\CategoryNodePageSearchByCategoryStoreEventsWriterInterface
     */
    public function createCategoryNodePageSearchByCategoryStoreEventsWriter(): CategoryNodePageSearchByCategoryStoreEventsWriterInterface
    {
        return new CategoryNodePageSearchByCategoryStoreEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsWriterInterface
     */
    public function createCategoryNodePageSearchByCategoryTemplateEventsWriter(): CategoryNodePageSearchByCategoryTemplateEventsWriterInterface
    {
        return new CategoryNodePageSearchByCategoryTemplateEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryAttribute\CategoryNodePageSearchByCategoryAttributeEventsDeleterInterface
     */
    public function createCategoryNodePageSearchByCategoryAttributeEventsDeleter(): CategoryNodePageSearchByCategoryAttributeEventsDeleterInterface
    {
        return new CategoryNodePageSearchByCategoryAttributeEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchDeleter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Deleter\Category\CategoryNodePageSearchByCategoryEventsDeleterInterface
     */
    public function createCategoryNodePageSearchByCategoryEventsDeleter(): CategoryNodePageSearchByCategoryEventsDeleterInterface
    {
        return new CategoryNodePageSearchByCategoryEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchDeleter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryTemplate\CategoryNodePageSearchByCategoryTemplateEventsDeleterInterface
     */
    public function createCategoryNodePageSearchByCategoryTemplateEventsDeleter(): CategoryNodePageSearchByCategoryTemplateEventsDeleterInterface
    {
        return new CategoryNodePageSearchByCategoryTemplateEventsDeleter(
            $this->getEventBehaviorFacade(),
            $this->createCategoryNodePageSearchDeleter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractorInterface
     */
    public function createCategoryNodeExtractor(): CategoryNodeExtractorInterface
    {
        return new CategoryNodeExtractor();
    }
}
