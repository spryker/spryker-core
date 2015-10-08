<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\StorageCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Zed\Collector\Business\CollectorFacade;
use SprykerFeature\Zed\Storage\Communication\Table\StorageTable;
use SprykerFeature\Zed\Storage\StorageDependencyProvider;

/**
 * @method StorageCommunication getFactory()
 */
class StorageDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return StorageTable
     */
    public function createStorageTable()
    {
        $storageClient = $this->createStorageClient();

        return $this->getFactory()->createTableStorageTable($storageClient);
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
