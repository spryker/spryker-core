<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilder;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilderInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilter;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilterInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReader;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReaderInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer\ProductOfferAvailabilityStorageWriter;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer\ProductOfferAvailabilityStorageWriterInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageEntityManagerInterface getEntityManager()
 */
class ProductOfferAvailabilityStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer\ProductOfferAvailabilityStorageWriterInterface
     */
    public function createProductOfferAvailabilityStorageWriter(): ProductOfferAvailabilityStorageWriterInterface
    {
        return new ProductOfferAvailabilityStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductOfferAvailabilityFacade(),
            $this->getSynchronizationService(),
            $this->getRepository(),
            $this->createProductOfferAvailabilityRequestBuilder(),
            $this->createProductOfferAvailabilityRequestFilter(),
            $this->getConfig()->isSendingToQueue(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilterInterface
     */
    public function createProductOfferAvailabilityRequestFilter(): ProductOfferAvailabilityRequestFilterInterface
    {
        return new ProductOfferAvailabilityRequestFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilderInterface
     */
    public function createProductOfferAvailabilityRequestBuilder(): ProductOfferAvailabilityRequestBuilderInterface
    {
        return new ProductOfferAvailabilityRequestBuilder(
            $this->createStoreReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader\StoreReaderInterface
     */
    public function createStoreReader(): StoreReaderInterface
    {
        return new StoreReader(
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface
     */
    public function getProductOfferAvailabilityFacade(): ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::FACADE_PRODUCT_OFFER_AVAILABILITY_FACADE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductOfferAvailabilityStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferAvailabilityStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::FACADE_STORE);
    }
}
