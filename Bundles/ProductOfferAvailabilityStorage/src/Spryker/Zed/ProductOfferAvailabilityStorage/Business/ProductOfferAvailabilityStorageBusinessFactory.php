<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer\ProductOfferAvailabilityStorageWriter;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer\ProductOfferAvailabilityStorageWriterInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface;
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
            $this->getConfig()->isSendingToQueue()
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
}
