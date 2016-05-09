<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Elastica\ResultSet;
use Elastica\SearchableInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

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
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        $query = $searchQuery->getSearchQuery($requestParameters);
        $rawSearchResult = $this->searchableInterface->search($query);

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
     * @return mixed
     */
    protected function formatSearchResults(array $resultFormatters, ResultSet $rawSearchResult, array $requestParameters)
    {
        $formattedSearchResult = [];

        foreach ($resultFormatters as $resultFormatter) {
            $formattedSearchResult = array_merge(
                $formattedSearchResult,
                $resultFormatter->formatResult($rawSearchResult, $requestParameters)
            );
        }

        return $formattedSearchResult;
    }

}
