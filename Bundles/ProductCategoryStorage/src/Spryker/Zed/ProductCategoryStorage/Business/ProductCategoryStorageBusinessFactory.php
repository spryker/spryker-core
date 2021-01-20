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
            $this->getEventBehaviorFacade(),
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
            $this->getRepository(),
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
}
