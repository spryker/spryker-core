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
use Spryker\Zed\Storage\StorageConfig;

/**
 * @method StorageConfig getConfig()
 */
class StorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return StorageTable
     */
    public function createStorageTable()
    {
        $storageClient = $this->getStorageClient();

        return new StorageTable($storageClient);
    }

    /**
     * @return StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return StorageClientInterface
     */
    public function createStorageClient()
    {
        trigger_error('Deprecated, use getStorageClient() instead.', E_USER_DEPRECATED);

        return $this->getStorageClient();
    }

    /**
     * @return CollectorFacade
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::FACADE_COLLECTOR);
    }

}
