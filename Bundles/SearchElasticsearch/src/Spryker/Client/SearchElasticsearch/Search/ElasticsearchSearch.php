<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Search;

use Elastica\Exception\ResponseException;
use Elastica\ResultSet;
use Spryker\Client\SearchElasticsearch\Exception\SearchResponseException;
use Spryker\Client\SearchElasticsearch\Provider\SearchElasticsearchClientProvider;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

class ElasticsearchSearch /*implements SearchInterface*/
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Provider\SearchElasticsearchClientProvider
     */
    protected $indexClientProvider;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Provider\SearchElasticsearchClientProvider $indexClientProvider
     * @param \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface $indexNameResolver
     */
    public function __construct(SearchElasticsearchClientProvider $indexClientProvider, IndexNameResolverInterface $indexNameResolver)
    {
        $this->indexClientProvider = $indexClientProvider;
        $this->indexNameResolver = $indexNameResolver;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): ResultSet
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
    protected function formatSearchResults(array $resultFormatters, ResultSet $rawSearchResult, array $requestParameters)
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
    protected function executeQuery(QueryInterface $query)
    {
        try {
            $indexName = $this->getIndexName($query);

            $query = $query->getSearchQuery();
            $client = $this->indexClientProvider->getClient($indexName);
            $rawSearchResult = $client->search($query);
        } catch (ResponseException $e) {
            $rawQuery = json_encode($query->toArray());

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
     * @return string|null
     */
    protected function getIndexName(QueryInterface $query): ?string
    {
        $indexName = null;

        if (method_exists($query, 'getIndexName')) {
            $indexName = $this->indexNameResolver->resolve($query->getIndexName());
        }

        return $indexName;
    }
}
