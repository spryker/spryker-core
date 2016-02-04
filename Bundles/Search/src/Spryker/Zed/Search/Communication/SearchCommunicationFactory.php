<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Communication;

use Spryker\Zed\Search\Communication\Table\SearchTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Storage\Communication\Table\StorageTable
     */
    public function createSearchTable()
    {
        $searchClient = $this->getSearchClient();

        return new SearchTable($searchClient);
    }

    /**
     * @return \Spryker\Client\Search\SearchClient
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacade
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->getConfig()->getElasticaDocumentType();
    }

}
