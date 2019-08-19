<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Elastica\Exception\ResponseException;
use Elastica\ResultSet;
use Elastica\SearchableInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Exception\SearchResponseException;

class ElasticsearchSearchHandler implements SearchHandlerInterface
{
    /**
     * @var \Elastica\SearchableInterface
     */
    protected $searchableInterface;

    /**
     * @param \Elastica\SearchableInterface $searchableInterface
     */
    public function __construct(SearchableInterface $searchableInterface)
    {
        $this->searchableInterface = $searchableInterface;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        $query = $searchQuery->getSearchQuery();
        $rawSearchResult = $this->executeQuery($query);

        if (!$resultFormatters) {
            return $rawSearchResult;
        }

        return $this->formatSearchResults($resultFormatters, $rawSearchResult, $requestParameters);
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
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
     * @param \Elastica\Query $query
     *
     * @throws \Spryker\Client\Search\Exception\SearchResponseException
     *
     * @return \Elastica\ResultSet
     */
    protected function executeQuery($query)
    {
        try {
            $rawSearchResult = $this->searchableInterface->search($query);
        } catch (ResponseException $e) {
            $rawQuery = json_encode($query->toArray());

            throw new SearchResponseException(
                sprintf("Search failed with the following reason: %s. Query: %s", $e->getMessage(), $rawQuery),
                $e->getCode(),
                $e
            );
        }

        return $rawSearchResult;
    }
}
