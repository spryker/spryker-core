<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Communication;

use Spryker\Zed\Search\Communication\Table\SearchTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Client\Search\SearchClient;
use Spryker\Zed\Collector\Business\CollectorFacade;
use Spryker\Zed\Search\SearchConfig;
use Spryker\Zed\Search\SearchDependencyProvider;
use Spryker\Zed\Storage\Communication\Table\StorageTable;

/**
 * @method SearchConfig getConfig()
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
     * @deprecated Use getSearchClient() instead.
     *
     * @return \Spryker\Client\Search\SearchClient
     */
    public function createSearchClient()
    {
        trigger_error('Deprecated, use getSearchClient() instead.', E_USER_DEPRECATED);

        return $this->getSearchClient();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacade
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @deprecated Use getCollectorFacade() instead.
     *
     * @return \Spryker\Zed\Collector\Business\CollectorFacade
     */
    public function createCollectorFacade()
    {
        trigger_error('Deprecated, use getCollectorFacade() instead.', E_USER_DEPRECATED);

        return $this->getCollectorFacade();
    }

    /**
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->getConfig()->getElasticaDocumentType();
    }

}
