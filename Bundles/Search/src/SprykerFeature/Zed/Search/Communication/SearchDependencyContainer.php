<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Communication;

use SprykerFeature\Zed\Search\Communication\Table\SearchTable;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Client\Search\SearchClient;
use SprykerFeature\Zed\Collector\Business\CollectorFacade;
use SprykerFeature\Zed\Search\SearchConfig;
use SprykerFeature\Zed\Search\SearchDependencyProvider;
use SprykerFeature\Zed\Storage\Communication\Table\StorageTable;

/**
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

        return new SearchTable($searchClient);
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
