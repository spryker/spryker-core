<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManagerStorage;

use Spryker\Client\FileManagerStorage\Storage\FileManagerStorage;
use Spryker\Client\Kernel\AbstractFactory;

class FileManagerStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\FileManagerStorage\Storage\FileManagerStorageInterface
     */
    public function createFileStorage()
    {
        return new FileManagerStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\FileManagerStorage\Dependency\Client\FileManagerStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\FileManagerStorage\Dependency\Service\FileManagerStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
