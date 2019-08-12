<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\QueryString;
use Spryker\Client\SearchExtension\Dependency\Plugin\NamedQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SearchStringQuery implements QueryInterface, NamedQueryInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getIndexName(): string
    {
        return 'page';
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
