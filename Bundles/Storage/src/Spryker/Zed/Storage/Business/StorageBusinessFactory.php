<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Client\Storage\StorageClient;
use Spryker\Zed\Storage\Business\Model\Storage;
use Spryker\Zed\Storage\StorageDependencyProvider;
use Spryker\Zed\Storage\StorageConfig;

/**
 * @method StorageConfig getConfig()
 */
class StorageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Storage
     */
    public function createStorage()
    {
        return new Storage(
            $this->createStorageClient()
        );
    }

    /**
     * @return StorageClient
     */
    private function createStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

}
