<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleter;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleter;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferCriteriaFilterTransferFactory;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferStorageWriterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 */
class MerchantProductOfferStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriterInterface
     */
    public function createProductConcreteProductOffersStorageWriter(): ProductConcreteOffersStorageWriterInterface
    {
        return new ProductConcreteOffersStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createProductConcreteProductOffersStorageDeleter(),
            $this->getStoreFacade(),
            $this->getProductOfferCriteriaFilterTransferFactory()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferStorageWriterInterface
     */
    public function createProductOfferStorageWriter(): ProductOfferStorageWriterInterface
    {
        return new ProductOfferStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createProductOfferStorageDeleter(),
            $this->getStoreFacade(),
            $this->getProductOfferCriteriaFilterTransferFactory()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface
     */
    public function createProductConcreteProductOffersStorageDeleter(): ProductConcreteOffersStorageDeleterInterface
    {
        return new ProductConcreteOffersStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface
     */
    public function createProductOfferStorageDeleter(): ProductOfferStorageDeleterInterface
    {
        return new ProductOfferStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProductOfferStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantProductOfferStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferCriteriaFilterTransferFactory
     */
    protected function getProductOfferCriteriaFilterTransferFactory(): ProductOfferCriteriaFilterTransferFactory
    {
        return new ProductOfferCriteriaFilterTransferFactory();
    }
}
