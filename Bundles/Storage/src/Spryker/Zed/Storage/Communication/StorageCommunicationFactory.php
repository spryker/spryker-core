<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Storage\Communication\Table\StorageTable;
use Spryker\Zed\Storage\Dependency\Service\StorageToUtilSanitizeServiceInterface;
use Spryker\Zed\Storage\StorageDependencyProvider;

/**
 * @method \Spryker\Zed\Storage\StorageConfig getConfig()
 * @method \Spryker\Zed\Storage\Business\StorageFacadeInterface getFacade()
 */
class StorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Storage\Communication\Table\StorageTable
     */
    public function createStorageTable()
    {
        return new StorageTable(
            $this->getStorageClient(),
            $this->getUtilSanitizeService(),
            $this->getConfig()->getGuiDefaultPageLength()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Storage\Dependency\Service\StorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): StorageToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
