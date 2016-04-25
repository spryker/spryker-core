<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;

class FulltextSearchQuery extends AbstractCatalogSearchQuery
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
     * @param array $requestParameters
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(array $requestParameters = [])
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
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery)
    {
        if (!empty($this->searchString)) {
            $matchQuery = $this->createFulltextSearchQuery($this->searchString);
        } else {
            $matchQuery = new Query\MatchAll();
        }

        $boolQuery = (new BoolQuery())
            ->addMust($matchQuery);

        $baseQuery->setQuery($boolQuery);

        return $baseQuery;
    }

}
