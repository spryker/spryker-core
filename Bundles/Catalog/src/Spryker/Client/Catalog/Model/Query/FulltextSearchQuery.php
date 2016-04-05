<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Index;
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
     * @param string $searchString
     */
    public function __construct($searchString)
    {
        $this->searchString = $searchString;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
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
        $match = (new Match())->setField(PageIndexMap::FULL_TEXT, $this->searchString);
        $boolQuery = (new BoolQuery())->addMust($match);

        $query->setQuery($boolQuery);

        return $query;
    }

}
