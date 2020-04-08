<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStoreClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapper;
use Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface;
use Spryker\Client\MerchantStorage\Mapper\UrlStorageMerchantMapper;
use Spryker\Client\MerchantStorage\Mapper\UrlStorageMerchantMapperInterface;
use Spryker\Client\MerchantStorage\Storage\MerchantStorageReader;
use Spryker\Client\MerchantStorage\Storage\MerchantStorageReaderInterface;

class MerchantStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantStorage\Storage\MerchantStorageReaderInterface
     */
    public function createMerchantStorageReader(): MerchantStorageReaderInterface
    {
        return new MerchantStorageReader(
            $this->createMerchantStorageMapper(),
            $this->getSynchronizationService(),
            $this->getStorageClient(),
            $this->getUtilEncodingService(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface
     */
    public function createMerchantStorageMapper(): MerchantStorageMapperInterface
    {
        return new MerchantStorageMapper();
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Mapper\UrlStorageMerchantMapperInterface
     */
    public function createUrlStorageMerchantMapper(): UrlStorageMerchantMapperInterface
    {
        return new UrlStorageMerchantMapper(
            $this->getSynchronizationService(),
            $this->getStorageClient(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStoreClientInterface
     */
    public function getStoreClient(): MerchantStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::CLIENT_STORE);
    }
}
