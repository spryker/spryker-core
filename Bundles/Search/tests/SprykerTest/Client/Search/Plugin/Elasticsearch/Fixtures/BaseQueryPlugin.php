<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\Fixtures;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

class BaseQueryPlugin implements QueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var string|null
     */
    protected $searchString;

    public function __construct()
    {
        $this->query = (new Query())
            ->setQuery(new BoolQuery());
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString): void
    {
        $this->searchString = $searchString;
    }

    /**
     * @return string|null
     */
    public function getSearchString()
    {
        return $this->searchString;
    }
}
