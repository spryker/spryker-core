<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Search;

use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Index;
use Elastica\ResultSet;
use Spryker\Client\SearchElasticsearch\Exception\SearchResponseException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

class Search implements SearchInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @param \Elastica\Client $client
     * @param \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface $indexNameResolver
     */
    public function __construct(Client $client, IndexNameResolverInterface $indexNameResolver)
    {
        $this->client = $client;
        $this->indexNameResolver = $indexNameResolver;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        $rawSearchResult = $this->executeQuery($searchQuery);

        if (!$resultFormatters) {
            return $rawSearchResult;
        }

        return $this->formatSearchResults($resultFormatters, $rawSearchResult, $requestParameters);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param \Elastica\ResultSet $rawSearchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResults(array $resultFormatters, ResultSet $rawSearchResult, array $requestParameters): array
    {
        $formattedSearchResult = [];

        foreach ($resultFormatters as $resultFormatter) {
            $formattedSearchResult[$resultFormatter->getName()] = $resultFormatter->formatResult($rawSearchResult, $requestParameters);
        }

        return $formattedSearchResult;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\SearchResponseException
     *
     * @return \Elastica\ResultSet
     */
    protected function executeQuery(QueryInterface $query): ResultSet
    {
        try {
            $index = $this->getIndexForQuery($query);
            $rawSearchResult = $index->search(
                $query->getSearchQuery()
            );
        } catch (ResponseException $e) {
            $rawQuery = json_encode($query->getSearchQuery()->toArray());

            throw new SearchResponseException(
                sprintf('Search failed with the following reason: %s. Query: %s', $e->getMessage(), $rawQuery),
                $e->getCode(),
                $e
            );
        }

        return $rawSearchResult;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     *
     * @return \Elastica\Index
     */
    protected function getIndexForQuery(QueryInterface $query): Index
    {
        $indexName = $this->getIndexName($query);

        return $this->client->getIndex($indexName);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface $query
     *
     * @return string|null
     */
    protected function getIndexName(QueryInterface $query): ?string
    {
        $sourceName = '';
        $searchContextTransfer = $query->getSearchContext();

        if (method_exists($searchContextTransfer, 'getElasticsearchContext')) {
            $sourceName = $searchContextTransfer->getElasticsearchContext()->getSourceName();
        }

        return $this->indexNameResolver->resolve($sourceName);
    }
}
