<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilder;
use Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Deleter\ProductCategoryStorageDeleter;
use Spryker\Zed\ProductCategoryStorage\Business\Deleter\ProductCategoryStorageDeleterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReader;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReader;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\Category\ProductCategoryStorageByCategoryEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\Category\ProductCategoryStorageByCategoryEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryStore\ProductCategoryStorageByCategoryStoreEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryStore\ProductCategoryStorageByCategoryStoreEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryAttribute\ProductCategoryStorageByCategoryAttributeEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryAttribute\ProductCategoryStorageByCategoryAttributeEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryNode\ProductCategoryStorageByCategoryNodeEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryNode\ProductCategoryStorageByCategoryNodeEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryUrl\ProductCategoryStorageByCategoryUrlEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryUrl\ProductCategoryStorageByCategoryUrlEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategory\ProductCategoryStorageByProductCategoryEventsWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategory\ProductCategoryStorageByProductCategoryEventsWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriter;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface getEntityManager()
 */
class ProductCategoryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface
     */
    public function createProductCategoryStorageWriter(): ProductCategoryStorageWriterInterface
    {
        return new ProductCategoryStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreFacade(),
            $this->createProductAbstractReader(),
            $this->createProductCategoryStorageReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Deleter\ProductCategoryStorageDeleterInterface
     */
    public function createProductCategoryStorageDeleter(): ProductCategoryStorageDeleterInterface
    {
        return new ProductCategoryStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->createProductAbstractReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getCategoryFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReaderInterface
     */
    public function createProductCategoryStorageReader(): ProductCategoryStorageReaderInterface
    {
        return new ProductCategoryStorageReader(
            $this->createCategoryTreeBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilderInterface
     */
    public function createCategoryTreeBuilder(): CategoryTreeBuilderInterface
    {
        return new CategoryTreeBuilder(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface
     */
    public function getCategoryFacade(): ProductCategoryStorageToCategoryInterface
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductCategoryStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductCategoryStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\Category\ProductCategoryStorageByCategoryEventsWriterInterface
     */
    public function createProductCategoryStorageByCategoryEventsWriter(): ProductCategoryStorageByCategoryEventsWriterInterface
    {
        return new ProductCategoryStorageByCategoryEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductCategoryStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryStore\ProductCategoryStorageByCategoryStoreEventsWriterInterface
     */
    public function createProductCategoryStorageByCategoryStoreEventsWriter(): ProductCategoryStorageByCategoryStoreEventsWriterInterface
    {
        return new ProductCategoryStorageByCategoryStoreEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductAbstractReader(),
            $this->createProductCategoryStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryAttribute\ProductCategoryStorageByCategoryAttributeEventsWriterInterface
     */
    public function createProductCategoryStorageByCategoryAttributeEventsWriter(): ProductCategoryStorageByCategoryAttributeEventsWriterInterface
    {
        return new ProductCategoryStorageByCategoryAttributeEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductCategoryStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryNode\ProductCategoryStorageByCategoryNodeEventsWriterInterface
     */
    public function createProductCategoryStorageByCategoryNodeEventsWriter(): ProductCategoryStorageByCategoryNodeEventsWriterInterface
    {
        return new ProductCategoryStorageByCategoryNodeEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductCategoryStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryUrl\ProductCategoryStorageByCategoryUrlEventsWriterInterface
     */
    public function createProductCategoryStorageByCategoryUrlEventsWriter(): ProductCategoryStorageByCategoryUrlEventsWriterInterface
    {
        return new ProductCategoryStorageByCategoryUrlEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->getCategoryFacade(),
            $this->createProductCategoryStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategory\ProductCategoryStorageByProductCategoryEventsWriterInterface
     */
    public function createProductCategoryStorageByProductCategoryEventsWriter(): ProductCategoryStorageByProductCategoryEventsWriterInterface
    {
        return new ProductCategoryStorageByProductCategoryEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductCategoryStorageWriter()
        );
    }
}
