<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStorageClientInterface;
use Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface;
use Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOfferStorage\Expander\ProductViewOfferExpander;
use Spryker\Client\ProductOfferStorage\Expander\ProductViewOfferExpanderInterface;
use Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapper;
use Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapperInterface;
use Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReader;
use Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReaderInterface;
use Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageKeyGenerator;
use Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface;
use Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageReader;
use Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageReaderInterface;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class ProductOfferStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getStorageClient(),
            $this->createProductOfferMapper(),
            $this->getUtilEncodingService(),
            $this->createProductOfferStorageKeyGenerator(),
            $this->getProductOfferStorageCollectionSorterPlugin(),
            $this->getProductOfferStorageExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReaderInterface
     */
    public function createProductConcreteDefaultProductOfferReader(): ProductConcreteDefaultProductOfferReaderInterface
    {
        return new ProductConcreteDefaultProductOfferReader($this->getProductOfferReferenceStrategyPlugins());
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapperInterface
     */
    public function createProductOfferMapper(): ProductOfferMapperInterface
    {
        return new ProductOfferMapper();
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface
     */
    public function createProductOfferStorageKeyGenerator(): ProductOfferStorageKeyGeneratorInterface
    {
        return new ProductOfferStorageKeyGenerator(
            $this->getSynchronizationService(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Expander\ProductViewOfferExpanderInterface
     */
    public function createProductViewOfferExpander(): ProductViewOfferExpanderInterface
    {
        return new ProductViewOfferExpander(
            $this->createProductConcreteDefaultProductOfferReader(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductOfferStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductOfferStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface>
     */
    public function getProductOfferReferenceStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_REFERENCE_STRATEGY);
    }

    /**
     * @return array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface>
     */
    public function getProductOfferStorageExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_STORAGE_EXPANDER);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    public function getProductOfferStorageCollectionSorterPlugin(): ProductOfferStorageCollectionSorterPluginInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PLUGIN_PRODUCT_OFFER_STORAGE_COLLECTION_SORTER);
    }
}
