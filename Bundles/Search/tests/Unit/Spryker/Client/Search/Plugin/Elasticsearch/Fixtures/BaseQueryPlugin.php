<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\Fixtures;

use Elastica\Query;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class BaseQueryPlugin implements QueryInterface
{

    /**
     * @var \Elastica\Query
     */
    protected $query;

    public function __construct()
    {
        $this->query = (new Query())
            ->setQuery(new Query\BoolQuery());
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

}
