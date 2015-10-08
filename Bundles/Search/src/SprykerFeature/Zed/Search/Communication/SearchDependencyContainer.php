<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Client\Search\Service\SearchClient;
use SprykerFeature\Zed\Collector\Business\CollectorFacade;
use SprykerFeature\Zed\Search\SearchConfig;
use SprykerFeature\Zed\Search\SearchDependencyProvider;
use SprykerFeature\Zed\Storage\Communication\Table\StorageTable;

/**
 * @method SearchCommunication getFactory()
 * @method SearchConfig getConfig()
 */
class SearchDependencyContainer extends AbstractCommunicationDependencyContainer
{

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
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return CollectorFacade
     */
    public function createCollectorFacade()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::FACADE_COLLECTOR);
    }

}
