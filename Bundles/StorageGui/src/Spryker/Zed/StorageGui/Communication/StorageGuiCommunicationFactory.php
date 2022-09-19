<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StorageGui\Communication\Table\StorageTable;
use Spryker\Zed\StorageGui\Dependency\Client\StorageGuiToStorageClientInterface;
use Spryker\Zed\StorageGui\Dependency\Facade\StorageGuiToStorageFacadeInterface;
use Spryker\Zed\StorageGui\Dependency\Service\StorageGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\StorageGui\StorageGuiDependencyProvider;

/**
 * @method \Spryker\Zed\StorageGui\StorageGuiConfig getConfig()
 */
class StorageGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StorageGui\Communication\Table\StorageTable
     */
    public function createStorageTable(): StorageTable
    {
        return new StorageTable(
            $this->getStorageClient(),
            $this->getUtilSanitizeService(),
            $this->getConfig()->getGuiDefaultPageLength(),
        );
    }

    /**
     * @return \Spryker\Zed\StorageGui\Dependency\Facade\StorageGuiToStorageFacadeInterface
     */
    public function getStorageFacade(): StorageGuiToStorageFacadeInterface
    {
        return $this->getProvidedDependency(StorageGuiDependencyProvider::FACADE_STORAGE);
    }

    /**
     * @return \Spryker\Zed\StorageGui\Dependency\Client\StorageGuiToStorageClientInterface
     */
    public function getStorageClient(): StorageGuiToStorageClientInterface
    {
        return $this->getProvidedDependency(StorageGuiDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\StorageGui\Dependency\Service\StorageGuiToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): StorageGuiToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(StorageGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
