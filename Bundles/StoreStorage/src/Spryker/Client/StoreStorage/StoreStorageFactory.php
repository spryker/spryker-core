<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface;
use Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface;
use Spryker\Client\StoreStorage\Expander\StoreExpander;
use Spryker\Client\StoreStorage\Expander\StoreExpanderInterface;
use Spryker\Client\StoreStorage\Reader\StoreListReader;
use Spryker\Client\StoreStorage\Reader\StoreStorageReader;
use Spryker\Client\StoreStorage\Reader\StoreStorageReaderInterface;

class StoreStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\StoreStorage\Reader\StoreStorageReaderInterface
     */
    public function createStoreStorageReader(): StoreStorageReaderInterface
    {
        return new StoreStorageReader(
            $this->getSynchronizationService(),
            $this->getStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\StoreStorage\Reader\StoreListReader
     */
    public function createStoreListReader(): StoreListReader
    {
        return new StoreListReader(
            $this->getSynchronizationService(),
            $this->getStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): StoreStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface
     */
    public function getStorageClient(): StoreStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\StoreStorage\Expander\StoreExpanderInterface
     */
    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander(
            $this->createStoreStorageReader(),
        );
    }
}
