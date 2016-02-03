<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Storage\Communication\Table\StorageTable;
use Spryker\Zed\Storage\StorageDependencyProvider;

/**
 * @method \Spryker\Zed\Storage\StorageConfig getConfig()
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

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function createStorageClient()
    {
        trigger_error('Deprecated, use getStorageClient() instead.', E_USER_DEPRECATED);

        return $this->getStorageClient();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacade
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::FACADE_COLLECTOR);
    }

}
