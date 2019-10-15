<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\QueryString;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchExtension\Plugin\Elasticsearch\Query\SearchStringQuery` instead.
 */
class SearchStringQuery implements QueryInterface
{
    /**
     * @var string
     */
    protected $searchString;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     */
    public function __construct($searchString, $limit = null, $offset = null)
    {
        $this->searchString = $searchString;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * {@inheritDoc}
     * - Returns a query object for string search.
     *
     * @api
     *
     * @return \Elastica\Query\MatchAll|\Elastica\Query
     */
    public function getSearchQuery()
    {
        $baseQuery = new Query();

        if (!empty($this->searchString)) {
            $query = $this->createStringQuery($this->searchString);
        } else {
            $query = new MatchAll();
        }

        $baseQuery->setQuery($query);

        $this->setLimit($baseQuery);
        $this->setOffset($baseQuery);

        $baseQuery->setExplain(true);

        return $baseQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\QueryString
     */
    protected function createStringQuery($searchString)
    {
        return new QueryString($searchString);
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setLimit($baseQuery)
    {
        if ($this->limit !== null) {
            $baseQuery->setSize($this->limit);
        }
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setOffset($baseQuery)
    {
        if ($this->offset !== null) {
            $baseQuery->setFrom($this->offset);
        }
    }
}
