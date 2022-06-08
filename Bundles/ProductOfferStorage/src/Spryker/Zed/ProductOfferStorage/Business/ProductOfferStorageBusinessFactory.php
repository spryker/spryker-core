<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleter;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleter;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReader;
use Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriter;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriterInterface;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProvider;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferStorageWriter;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferStorageWriterInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferStorage\ProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 */
class ProductOfferStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriterInterface
     */
    public function createProductConcreteProductOffersStorageWriter(): ProductConcreteOffersStorageWriterInterface
    {
        return new ProductConcreteOffersStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->createProductConcreteProductOffersStorageDeleter(),
            $this->getStoreFacade(),
            $this->createProductOfferStorageReader(),
            $this->getProductOfferStorageFilterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferStorageWriterInterface
     */
    public function createProductOfferStorageWriter(): ProductOfferStorageWriterInterface
    {
        return new ProductOfferStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->createProductOfferStorageDeleter(),
            $this->getStoreFacade(),
            $this->createProductOfferStorageReader(),
            $this->getProductOfferStorageFilterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface
     */
    public function createProductConcreteProductOffersStorageDeleter(): ProductConcreteProductOffersStorageDeleterInterface
    {
        return new ProductConcreteProductOffersStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface
     */
    public function createProductOfferStorageDeleter(): ProductOfferStorageDeleterInterface
    {
        return new ProductOfferStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->createProductOfferStorageReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getRepository(),
            $this->createProductOfferCriteriaTransferProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductOfferStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface
     */
    public function createProductOfferCriteriaTransferProvider(): ProductOfferCriteriaTransferProviderInterface
    {
        return new ProductOfferCriteriaTransferProvider();
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface>
     */
    public function getProductOfferStorageFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_STORAGE_FILTER);
    }
}
