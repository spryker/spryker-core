<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleter;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractor;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface;
use Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapper;
use Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapper;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriter;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface;
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
            $this->createCategoryNodePageSearchExtractor()
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
            $this->createCategoryNodePageSearchExtractor()
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
     * @return \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface
     */
    protected function createCategoryNodePageSearchExtractor(): CategoryNodePageSearchExtractorInterface
    {
        return new CategoryNodePageSearchExtractor();
    }
}
