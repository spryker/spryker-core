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
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchElasticsearch\Exception\SearchResponseException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class Search implements SearchInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @param \Elastica\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, SearchContextTransfer $searchContextTransfer, array $resultFormatters = [], array $requestParameters = [])
    {
        $rawSearchResult = $this->executeQuery($searchQuery, $searchContextTransfer);

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
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\SearchResponseException
     *
     * @return \Elastica\ResultSet
     */
    protected function executeQuery(QueryInterface $query, SearchContextTransfer $searchContextTransfer): ResultSet
    {
        try {
            $searchQuery = $query->getSearchQuery();
            $index = $this->getIndexForQuery($searchContextTransfer);
            $rawSearchResult = $index->search($searchQuery);
        } catch (ResponseException $e) {
            $rawQuery = json_encode($searchQuery->toArray());

            throw new SearchResponseException(
                sprintf('Search failed with the following reason: %s. Query: %s', $e->getMessage(), $rawQuery),
                $e->getCode(),
                $e
            );
        }

        return $rawSearchResult;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Elastica\Index
     */
    protected function getIndexForQuery(SearchContextTransfer $searchContextTransfer): Index
    {
        $indexName = $this->getIndexName($searchContextTransfer);

        return $this->client->getIndex($indexName);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return string
     */
    protected function getIndexName(SearchContextTransfer $searchContextTransfer): string
    {
        return $searchContextTransfer->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireSourceName()
            ->getSourceName();
    }
}
