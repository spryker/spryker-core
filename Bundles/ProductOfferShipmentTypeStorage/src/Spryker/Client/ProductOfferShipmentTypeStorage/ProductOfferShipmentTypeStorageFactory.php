<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Expander\ProductOfferStorageExpander;
use Spryker\Client\ProductOfferShipmentTypeStorage\Expander\ProductOfferStorageExpanderInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGenerator;
use Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGeneratorInterface;

class ProductOfferShipmentTypeStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Expander\ProductOfferStorageExpanderInterface
     */
    public function createProductOfferStorageExpander(): ProductOfferStorageExpanderInterface
    {
        return new ProductOfferStorageExpander(
            $this->createProductOfferShipmentTypeKeyGenerator(),
            $this->getStorageClient(),
            $this->getStoreClient(),
            $this->getShipmentTypeStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGeneratorInterface
     */
    public function createProductOfferShipmentTypeKeyGenerator(): ProductOfferShipmentTypeKeyGeneratorInterface
    {
        return new ProductOfferShipmentTypeKeyGenerator($this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductOfferShipmentTypeStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductOfferShipmentTypeStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferShipmentTypeStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_STORE);
    }
}
