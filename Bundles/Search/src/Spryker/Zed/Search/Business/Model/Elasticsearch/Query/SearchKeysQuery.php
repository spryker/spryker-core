<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Model\Query\QueryInterface;

class SearchKeysQuery implements QueryInterface
{
    const PARAM_LIMIT = 'length';
    const PARAM_OFFSET = 'start';

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @param string $searchString
     */
    public function __construct($searchString)
    {
        $this->searchString = $searchString;
    }

    /**
     * @param array $requestParameters
     *
     * @return \Elastica\Query\MatchAll
     */
    public function getSearchQuery(array $requestParameters = [])
    {
        $baseQuery = new Query();

        if (!empty($this->searchString)) {
            $query = $this->createFullTextSearchQuery($this->searchString);
        } else {
            $query = new MatchAll();
        }

        $baseQuery->setQuery($query);

        $this->setLimit($baseQuery, $requestParameters);
        $this->setOffset($baseQuery, $requestParameters);

        return $baseQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createFullTextSearchQuery($searchString)
    {
        $fullTextMatch = (new Match())->setField(PageIndexMap::FULL_TEXT, $searchString);
        $fullTextBoostedMatch = (new Match())->setField(PageIndexMap::FULL_TEXT_BOOSTED, $searchString);

        $boolQuery = (new BoolQuery())
            ->addShould($fullTextMatch)
            ->addShould($fullTextBoostedMatch)
            ->setMinimumNumberShouldMatch(1);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query $baseQuery
     * @param array $requestParameters
     *
     * @return void
     */
    protected function setLimit($baseQuery, array $requestParameters)
    {
        if ($requestParameters[self::PARAM_LIMIT]) {
            $baseQuery->setSize($requestParameters[self::PARAM_LIMIT]);
        }
    }

    /**
     * @param \Elastica\Query $baseQuery
     * @param array $requestParameters
     *
     * @return void
     */
    protected function setOffset($baseQuery, array $requestParameters)
    {
        if (isset($requestParameters[self::PARAM_OFFSET])) {
            $baseQuery->setFrom($requestParameters[self::PARAM_OFFSET]);
        }
    }

}
