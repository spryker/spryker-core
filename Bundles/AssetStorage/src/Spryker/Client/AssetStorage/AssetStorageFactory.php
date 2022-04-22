<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage;

use Spryker\Client\AssetStorage\Dependency\Client\AssetStorageToStorageClientInterface;
use Spryker\Client\AssetStorage\Dependency\Service\AssetStorageToSynchronizationServiceInterface;
use Spryker\Client\AssetStorage\Mapper\AssetStorageMapper;
use Spryker\Client\AssetStorage\Mapper\AssetStorageMapperInterface;
use Spryker\Client\AssetStorage\Reader\AssetStorageReader;
use Spryker\Client\AssetStorage\Reader\AssetStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AssetStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AssetStorage\Reader\AssetStorageReaderInterface
     */
    public function createAssetStorageReader(): AssetStorageReaderInterface
    {
        return new AssetStorageReader(
            $this->getStorageClient(),
            $this->getServiceSynchronization(),
            $this->createAssetStorageMapper(),
        );
    }

    /**
     * @return \Spryker\Client\AssetStorage\Mapper\AssetStorageMapperInterface
     */
    public function createAssetStorageMapper(): AssetStorageMapperInterface
    {
        return new AssetStorageMapper();
    }

    /**
     * @return \Spryker\Client\AssetStorage\Dependency\Service\AssetStorageToSynchronizationServiceInterface
     */
    public function getServiceSynchronization(): AssetStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(AssetStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\AssetStorage\Dependency\Client\AssetStorageToStorageClientInterface
     */
    public function getStorageClient(): AssetStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(AssetStorageDependencyProvider::CLIENT_STORAGE);
    }
}
