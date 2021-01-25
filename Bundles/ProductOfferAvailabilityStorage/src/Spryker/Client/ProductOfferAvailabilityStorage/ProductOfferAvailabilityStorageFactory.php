<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReader;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface;

class ProductOfferAvailabilityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface
     */
    public function createProductOfferAvailabilityStorageReader(): ProductOfferAvailabilityStorageReaderInterface
    {
        return new ProductOfferAvailabilityStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductOfferAvailabilityStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferAvailabilityStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductOfferAvailabilityStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferAvailabilityStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
