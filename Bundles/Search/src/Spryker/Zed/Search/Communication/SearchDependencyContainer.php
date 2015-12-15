<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Communication;

use Spryker\Zed\Search\Communication\Table\SearchTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Client\Search\SearchClient;
use Spryker\Zed\Collector\Business\CollectorFacade;
use Spryker\Zed\Search\SearchConfig;
use Spryker\Zed\Search\SearchDependencyProvider;
use Spryker\Zed\Storage\Communication\Table\StorageTable;

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
