<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Client\Storage\StorageClient;
use SprykerFeature\Zed\Storage\Business\Model\Storage;
use SprykerFeature\Zed\Storage\StorageDependencyProvider;

class StorageDependencyContainer extends AbstractBusinessDependencyContainer
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
