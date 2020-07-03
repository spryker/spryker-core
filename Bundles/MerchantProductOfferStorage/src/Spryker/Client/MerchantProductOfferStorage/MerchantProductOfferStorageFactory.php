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
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapper;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOffer;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReader;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageKeyGenerator;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReader;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class MerchantProductOfferStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getStorageClient(),
            $this->createMerchantProductOfferMapper(),
            $this->getMerchantStorageClient(),
            $this->getUtilEncodingService(),
            $this->createProductOfferStorageKeyGenerator(),
            $this->createProductConcreteDefaultProductOffer(),
            $this->getProductOfferStorageCollectionSorterPlugin(),
            $this->getProductOfferStorageExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface
     */
    public function createProductConcreteDefaultProductOffer(): ProductConcreteDefaultProductOfferInterface
    {
        return new ProductConcreteDefaultProductOffer($this->createProductConcreteDefaultProductOfferReader());
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface
     */
    public function createProductConcreteDefaultProductOfferReader(): ProductConcreteDefaultProductOfferReaderInterface
    {
        return new ProductConcreteDefaultProductOfferReader($this->getProductOfferReferenceStrategyPlugins());
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapperInterface
    {
        return new MerchantProductOfferMapper();
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface
     */
    public function createProductOfferStorageKeyGenerator(): ProductOfferStorageKeyGeneratorInterface
    {
        return new ProductOfferStorageKeyGenerator(
            $this->getSynchronizationService(),
            $this->getStoreClient()
        );
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
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface[]
     */
    public function getProductOfferReferenceStrategyPlugins(): array
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_REFERENCE_STRATEGY);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface[]
     */
    public function getProductOfferStorageExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_STORAGE_EXPANDER);
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

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantProductOfferStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    public function getProductOfferStorageCollectionSorterPlugin(): ProductOfferStorageCollectionSorterPluginInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::PLUGIN_PRODUCT_OFFER_STORAGE_COLLECTION_SORTER);
    }
}
