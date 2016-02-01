<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Storage\Business\Model\Storage;
use Spryker\Zed\Storage\StorageDependencyProvider;
use Spryker\Zed\Storage\StorageConfig;

/**
 * @method StorageConfig getConfig()
 */
class StorageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Storage\Business\Model\Storage
     */
    public function createStorage()
    {
        return new Storage(
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClient
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

}
