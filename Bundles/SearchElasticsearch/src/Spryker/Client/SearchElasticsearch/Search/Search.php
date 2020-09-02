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
use Spryker\Client\SearchElasticsearch\Exception\InvalidSearchQueryException;
use Spryker\Client\SearchElasticsearch\Exception\SearchResponseException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

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
        $searchContext = $this->getSearchContext($query);

        try {
            $index = $this->getIndexForQueryFromSearchContext($searchContext);
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
     * @deprecated Will be replaced with inline usage when SearchContextAwareQueryInterface is merged into QueryInterface.
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function getSearchContext($query): SearchContextTransfer
    {
        $this->assertSearchContextAwareQueryInterface($query);

        return $query->getSearchContext();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface $query
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\InvalidSearchQueryException
     *
     * @return void
     */
    protected function assertSearchContextAwareQueryInterface($query): void
    {
        if (!$query instanceof SearchContextAwareQueryInterface) {
            throw new InvalidSearchQueryException(
                sprintf(
                    'Query class %s doesn\'t implement %s interface.',
                    get_class($query),
                    SearchContextAwareQueryInterface::class
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Elastica\Index
     */
    protected function getIndexForQueryFromSearchContext(SearchContextTransfer $searchContextTransfer): Index
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
        $this->assertSearchContextTransferHasIndexName($searchContextTransfer);

        return $searchContextTransfer
            ->getElasticsearchContext()
            ->requireIndexName()
            ->getIndexName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    protected function assertSearchContextTransferHasIndexName(SearchContextTransfer $searchContextTransfer): void
    {
        $searchContextTransfer->requireElasticsearchContext()->getElasticsearchContext()->requireIndexName();
    }
}
