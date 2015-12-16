<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Collector\Business\CollectorFacade;
use Spryker\Zed\Storage\Communication\Table\StorageTable;
use Spryker\Zed\Storage\StorageDependencyProvider;

class StorageCommunicationFactory extends AbstractCommunicationFactory
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
