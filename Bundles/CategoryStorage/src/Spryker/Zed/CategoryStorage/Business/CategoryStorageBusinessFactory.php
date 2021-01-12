<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapper;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapperInterface;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapper;
use Spryker\Zed\CategoryStorage\Business\Mapper\CategoryNodeStorageMapperInterface;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorage;
use Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorageInterface;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilder;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriter;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface;
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
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface getEntityManager()
 */
class CategoryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage(): CategoryNodeStorageInterface
    {
        return new CategoryNodeStorage(
            $this->getQueryContainer(),
            $this->createCategoryNodeStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface
     */
    public function createCategoryNodeStorageWriter(): CategoryNodeStorageWriterInterface
    {
        return new CategoryNodeStorageWriter(
            $this->getEntityManager(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getCategoryFacade(),
            $this->getEventBehaviorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\Writer\CategoryTreeStorageWriterInterface
     */
    public function createCategoryTreeStorageWriter(): CategoryTreeStorageWriterInterface
    {
        return new CategoryTreeStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCategoryStorageNodeTreeBuilder(),
            $this->getCategoryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    public function createCategoryStorageNodeTreeBuilder(): CategoryStorageNodeTreeBuilderInterface
    {
        return new CategoryStorageNodeTreeBuilder(
            $this->getStoreFacade(),
            $this->createCategoryNodeStorageMapper()
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
}
