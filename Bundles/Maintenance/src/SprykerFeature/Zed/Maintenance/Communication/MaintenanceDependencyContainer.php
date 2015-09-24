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
use SprykerFeature\Zed\Maintenance\MaintenanceDependencyProvider;
use Symfony\Component\Validator\Validator;

/**
 * @method MaintenanceCommunication getFactory()
 */
class MaintenanceDependencyContainer extends AbstractCommunicationDependencyContainer
{


    /**
     * @return StorageTable
     */
    public function createStorageTable(){
        $storageClient = $this->getStorageClient();
        return $this->getFactory()->createTableStorageTable($storageClient);
    }

    /**
     * @return StorageTable
     */
    public function createSearchTable(){
        $searchClient = $this->getSearchClient();
        return $this->getFactory()->createTableSearchTable($searchClient);
    }

    /**
     * @return SearchClient
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(MaintenanceDependencyProvider::SEARCH_CLIENT);
    }

    /**
     * @return StorageClient
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(MaintenanceDependencyProvider::STORAGE_CLIENT);
    }

    public function getElasticaDocumentType()
    {
        return $this->getConfig()->getElasticaDocumentType();
    }

}
