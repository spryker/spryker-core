<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductResourceAliasStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductResourceAliasStorage\Dependency\Client\PriceProductResourceAliasStorageToStorageClientInterface;
use Spryker\Client\PriceProductResourceAliasStorage\Dependency\Service\PriceProductResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductAbstractStorageReader;
use Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductAbstractStorageReaderInterface;
use Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductConcreteStorageReader;
use Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductConcreteStorageReaderInterface;
use Spryker\Shared\Kernel\Store;

class PriceProductResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductAbstractStorageReaderInterface
     */
    public function createPriceProductAbstractStorageReader(): PriceProductAbstractStorageReaderInterface
    {
        return new PriceProductAbstractStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductResourceAliasStorage\Storage\PriceProductConcreteStorageReaderInterface
     */
    public function createPriceProductConcreteStorageReader(): PriceProductConcreteStorageReaderInterface
    {
        return new PriceProductConcreteStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Client\PriceProductResourceAliasStorageToStorageClientInterface
     */
    public function getStorageClient(): PriceProductResourceAliasStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Service\PriceProductResourceAliasStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): PriceProductResourceAliasStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::STORE);
    }
}
