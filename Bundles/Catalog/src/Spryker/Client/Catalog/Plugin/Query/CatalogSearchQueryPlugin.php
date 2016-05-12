<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class CatalogSearchQueryPlugin extends AbstractPlugin implements QueryInterface
{

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var int
     */
    protected $fullTextBoostedBoosting;

    /**
     * @param string $searchString
     * @param int $fullTextBoostedBoosting
     */
    public function __construct($searchString, $fullTextBoostedBoosting)
    {
        $this->searchString = $searchString;
        $this->fullTextBoostedBoosting = $fullTextBoostedBoosting;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $query = new Query();
        $query = $this->addFulltextSearchToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery)
    {
        if (!empty($this->searchString)) {
            $matchQuery = $this->createFulltextSearchQuery($this->searchString);
        } else {
            $matchQuery = new MatchAll();
        }

        $boolQuery = (new BoolQuery())
            ->addMust($matchQuery);

        $baseQuery->setQuery($boolQuery);

        return $baseQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery($searchString)
    {
        $fields = [
            PageIndexMap::FULL_TEXT,
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . $this->fullTextBoostedBoosting
        ];

        $matchQuery = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        return $matchQuery;
    }

}
