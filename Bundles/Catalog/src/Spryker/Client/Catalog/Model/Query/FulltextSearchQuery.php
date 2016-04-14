<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\Catalog\PageIndexMap;
use Spryker\Client\Search\Model\Query\QueryInterface;

class FulltextSearchQuery implements QueryInterface
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
     * @param string $searchString
     */
    public function __construct($searchString)
    {
        $this->searchString = $searchString;
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
    public function createSearchQuery()
    {
        $query = new Query();
        $query = $this->addFulltextSearchToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $query)
    {
        $fullTextMatch = (new Match())->setField(PageIndexMap::FULL_TEXT, $this->searchString);
        $fullTextBoostedMatch = (new Match())->setField(PageIndexMap::FULL_TEXT_BOOSTED, $this->searchString);

        $boolQuery = (new BoolQuery())
            ->addShould($fullTextMatch)
            ->addShould($fullTextBoostedMatch)
            ->setMinimumNumberShouldMatch(1);

        $query->setQuery($boolQuery);

        return $query;
    }

}
