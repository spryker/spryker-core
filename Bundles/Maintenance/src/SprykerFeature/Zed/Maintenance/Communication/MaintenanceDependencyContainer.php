<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\MaintenanceCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Client\Search\Service\SearchClient;
use SprykerFeature\Client\Storage\Service\StorageClient;
use SprykerFeature\Zed\Maintenance\Communication\Table\StorageTable;
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use SprykerFeature\Zed\Maintenance\MaintenanceDependencyProvider;

/**
 * @method MaintenanceCommunication getFactory()
 * @method MaintenanceConfig getConfig()
 */
class MaintenanceDependencyContainer extends AbstractCommunicationDependencyContainer
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
     * @return StorageTable
     */
    public function createSearchTable()
    {
        $searchClient = $this->createSearchClient();

        return $this->getFactory()->createTableSearchTable($searchClient);
    }

    /**
     * @return SearchClient
     */
    public function createSearchClient()
    {
        return $this->getProvidedDependency(MaintenanceDependencyProvider::SEARCH_CLIENT);
    }

    /**
     * @return StorageClient
     */
    public function createStorageClient()
    {
        return $this->getProvidedDependency(MaintenanceDependencyProvider::STORAGE_CLIENT);
    }

}
