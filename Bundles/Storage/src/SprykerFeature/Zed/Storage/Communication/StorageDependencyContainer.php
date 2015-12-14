<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Client\Storage\StorageClientInterface;
use SprykerFeature\Zed\Collector\Business\CollectorFacade;
use SprykerFeature\Zed\Storage\Communication\Table\StorageTable;
use SprykerFeature\Zed\Storage\StorageDependencyProvider;

class StorageDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return StorageTable
     */
    public function createStorageTable()
    {
        $storageClient = $this->createStorageClient();

        return new StorageTable($storageClient);
    }

    /**
     * @return StorageClientInterface
     */
    public function createStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return CollectorFacade
     */
    public function createCollectorFacade()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::FACADE_COLLECTOR);
    }

}
