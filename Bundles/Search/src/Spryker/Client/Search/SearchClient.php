<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchClient extends AbstractClient implements SearchClientInterface
{
    /**
     * Specification:
     * - Connects to Elasticsearch client if possible
     * - Throws exception if connection fails
     *
     * @api
     *
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection()
    {
        $this->getFactory()
            ->getElasticsearchClient()
            ->getStatus()
            ->getData();
    }

    /**
     * Specification:
     * - Expands the base search query with multiple query expanders
     * - All expanders use the same search configuration provided by this client
     * - The expanders use the given parameters to adapt to the request
     * - Returns the expanded query
     *
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = [])
    {
        foreach ($searchQueryExpanders as $searchQueryExpander) {
            $searchQuery = $searchQueryExpander->expandQuery($searchQuery, $requestParameters);
        }

        return $searchQuery;
    }

    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client
     * - If there's no result formatter given then the raw search result will be returned
     * - The formatted search result will be an associative array where the keys are the name and the values are the formatted results
     *
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return $this
            ->getFactory()
            ->createElasticsearchSearchHandler()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * Specification:
     * - Returns a statically cached instance (for performance reasons) of the search configuration
     * - The result is the union of the hard-coded and the dynamic configurations
     * - Dynamic configuration is provided by \Spryker\Client\Search\SearchDependencyProvider::createSearchConfigExpanderPlugins()
     *
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        return $this->getFactory()->getSearchConfig();
    }

    /**
     * Specification:
     * - Runs a simple full text search for the given search string
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|array
     */
    public function searchKeys($searchString, $limit = null, $offset = null)
    {
        $query = $this
            ->getFactory()
            ->createSearchKeysQuery($searchString, $limit, $offset);

        return $this->search($query);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|array
     */
    public function searchQueryString($searchString, $limit = null, $offset = null)
    {
        $query = $this
            ->getFactory()
            ->createSearchStringQuery($searchString, $limit, $offset);

        return $this->search($query);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read($key, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createReader()
            ->read($key, $typeName, $indexName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->write($dataSet, $typeName, $indexName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->writeBulk($searchDocumentTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->delete($dataSet, $typeName, $indexName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->deleteBulk($searchDocumentTransfers);
    }
}
