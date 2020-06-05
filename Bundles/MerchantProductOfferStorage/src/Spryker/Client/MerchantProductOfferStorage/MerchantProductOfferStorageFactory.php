<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapper;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOffer;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReader;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface;

class MerchantProductOfferStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createMerchantProductOfferMapper(),
            $this->getStoreClient(),
            $this->getMerchantStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface
     */
    public function createProductConcreteDefaultProductOffer(): ProductConcreteDefaultProductOfferInterface
    {
        return new ProductConcreteDefaultProductOffer(
            $this->createProductOfferStorageReader(),
            $this->getDefaultProductOfferPlugin()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapperInterface
    {
        return new MerchantProductOfferMapper();
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantProductOfferStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantProductOfferStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface
     */
    public function getDefaultProductOfferPlugin(): ProductOfferProviderPluginInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::PLUGIN_PRODUCT_OFFER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface
     */
    public function getStoreClient(): MerchantProductOfferStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductOfferStorageToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }
}
