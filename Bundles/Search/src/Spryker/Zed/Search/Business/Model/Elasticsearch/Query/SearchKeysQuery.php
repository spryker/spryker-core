<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class SearchKeysQuery implements QueryInterface
{

    const PARAM_LIMIT = 'length';
    const PARAM_OFFSET = 'start';

    const FULL_TEXT_BOOSTED_BOOSTING = 3;

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

        $baseQuery->setExplain(true);

        return $baseQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createFullTextSearchQuery($searchString)
    {
        $fields = [
            PageIndexMap::FULL_TEXT,
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . self::FULL_TEXT_BOOSTED_BOOSTING,
        ];

        $multiMatch = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        $boolQuery = (new BoolQuery())
            ->addMust($multiMatch);

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
