<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Storage\Communication\Table\StorageTable;
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
        $storageClient = $this->getStorageClient();

        return new StorageTable($storageClient);
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }
}
